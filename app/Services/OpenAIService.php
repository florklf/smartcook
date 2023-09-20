<?php

namespace App\Services;

use App\Models\Recipe;
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
}
