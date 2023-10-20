<?php

namespace App\Services;

use App\Models\Recipe;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Facade;
use OpenAI;

class OpenAIService extends Facade
{
  private $client;

  public function __construct() {
    $apiKey = getenv('OPENAI_API_KEY');
    $this->client = OpenAI::client($apiKey);
    return $this->client;
  }

  public function getClient()
  {
    return $this->client;
  }

  public function getIngredients(Recipe $recipe)
  {
    $ingredients_prompt = 'Provide the list of ingredients needed to cook a recipe titled "' . $recipe->name . '".\n' . 'To make it easier, you can extract the ingredients from the following recipe instructions if needed:\n' . implode("\n", $recipe->instructions) . '\n';
    $ingredients_response = $this->client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'The following is a list of ingredients needed to cook the recipe titled "' . $recipe->name . '". Return only the ingredients in the completion as a comma-separated list in french language. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
            ['role' => 'user', 'content' => $ingredients_prompt],
        ],
    ]);
    return array_map(fn($value) => ucfirst(trim($value)), explode(',', $ingredients_response['choices'][0]['message']['content']));
  }

  public function getRelatedRecipes(Recipe $recipe)
  {
    $related_recipes_prompt = 'Provide a comma-separated list of recipes that are similar (culinary-speaking) to "' . $recipe->name . '".\n' . 'Here is the list of all recipes in the database:\n' . implode("\n", Recipe::all()->except($recipe->id)->pluck('name')->toArray()) . '\n';
    $related_recipes_response = $this->client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'The following are recipes that is similar to "' . $recipe->name . '". Only return the name found in the completion as a comma-separated list. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
            ['role' => 'user', 'content' => $related_recipes_prompt],
        ],
    ]);
    $related_recipes = array_map(fn($value) => trim($value), explode(',', $related_recipes_response['choices'][0]['message']['content']));
    return Recipe::whereIn('name', $related_recipes)->where('id', '!=', $recipe->id)->get()->take(4);
  }

  public function getRecipes(Request $request)
  {
    $recipes_prompt = "Here is the list of all recipes in the database:\n" . implode("\n", Recipe::all()->pluck('name')->toArray()) . "\n";
    if ($request->input('search')) {
        $recipes_prompt .= 'Filter this list by only including those that correspond to this user search: ' . $request->input('search') . ".\n";
    }
    if (Auth::user() && Auth::user()->dietaryRestrictions()->allergies()->count() > 0) {
        $recipes_prompt .= 'Filter this list by including only recipes that are compatible with the following allergies: ' . implode(", ", Auth::user()->dietaryRestrictions()->allergies()->pluck('name')->toArray()) . ".\n";
    }
    if (Auth::user() && Auth::user()->dietaryRestrictions()->diets()->count() > 0) {
        $recipes_prompt .= 'Filter this list by including only recipes that are compatible with the following diets: ' . implode(", ", Auth::user()->dietaryRestrictions()->diets()->pluck('name')->toArray()) . ".\n";
    }

    $recipes_response = $this->client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Only return the filtered list found as a comma-separated list. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
            ['role' => 'user', 'content' => $recipes_prompt],
        ],
    ]);

    $recipes = $recipes_response['choices'][0]['message']['content'];
    $recipes = explode(',', $recipes);
    $recipes = Recipe::whereIn('name', Arr::map($recipes, fn($value) => trim($value)))->paginate(9);
    return $recipes;
  }
}
