<?php

namespace App\Http\Controllers;

use App\Models\Notation;
use App\Models\Recipe;
use Illuminate\View\View;
use App\Services\OpenAIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    /**
     * Show the recipes.
     * @return View
     */
    public function index(): View
    {
        $recipes = Recipe::all();
        return view('home', ['recipes' => $recipes]);
    }

    /**
     * Show the recipe.
     * @param Recipe $recipe
     * @return View
     */
    public function show(Recipe $recipe): View
    {
        $userHasNotated = Auth::user() && !Auth::user()->notations->contains('recipe_id', $recipe->id);
        $userHasFavorited = Auth::user() && Auth::user()->favoriteRecipe->contains('id', $recipe->id);
        $notations = Notation::where('recipe_id', $recipe->id)->orderBy('created_at', 'desc')->take(5)->get();

        $client = OpenAIService::getClient();
        
        // Ingredients
        $ingredients_prompt = 'Provide the list of ingredients needed to cook a recipe titled "' . $recipe->name . '".\n' . 'To make it easier, you can extract the ingredients from the following recipe instructions if needed:\n' . implode("\n", $recipe->instructions) . '\n';
        $ingredients_response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo-16k',
            'messages' => [
                ['role' => 'system', 'content' => 'The following is a list of ingredients needed to cook the recipe titled "' . $recipe->name . '". Return only the ingredients in the completion as a comma-separated list in french language. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
                ['role' => 'user', 'content' => $ingredients_prompt],
            ],
        ]);
        $ingredients = $ingredients_response['choices'][0]['message']['content'];
        $ingredients = explode(',', $ingredients);
        $ingredients = array_map(function ($value) {
            return ucfirst(trim($value));
        }, $ingredients);

        // Related recipes
        $related_recipes_prompt = 'Provide a comma-separated list of recipes that are similar (culinary-speaking) to "' . $recipe->name . '".\n' . 'Here is the list of all recipes in the database:\n' . implode("\n", Recipe::all()->except($recipe->id)->pluck('name')->toArray()) . '\n';
        $related_recipes_response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'The following are recipes that is similar to "' . $recipe->name . '". Only return the name found in the completion as a comma-separated list. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
                ['role' => 'user', 'content' => $related_recipes_prompt],
            ],
        ]);
        $related_recipes = $related_recipes_response['choices'][0]['message']['content'];
        $related_recipes = explode(',', $related_recipes);
        $related_recipes = array_map(function ($value) {
            return trim($value);
        }, $related_recipes);
        $related_recipes = Recipe::whereIn('name', $related_recipes)->where('id', '!=', $recipe->id)->get()->take(4);
        if ($related_recipes->count() == 0) {
            $related_recipes = Recipe::inRandomOrder()->take(4)->get();
        }

        return view('recipes.show', [
            'recipe' => $recipe,
            'notations' => $notations,
            'userHasNotated' => $userHasNotated,
            'userHasFavorited' => $userHasFavorited,
            'ingredients' => $ingredients,
            'related_recipes' => $related_recipes,
        ]);
    }

    /**
     * Favorite the recipe.
     * @param Recipe $recipe
     * @return View
     */
    public function favorite(Request $request): RedirectResponse
    {
        Auth::user()->favoriteRecipe()->toggle($request->recipe_id);
        return redirect()->back();
    }
}
