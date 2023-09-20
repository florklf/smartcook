<?php

namespace App\Http\Controllers;

use App\Models\Notation;
use App\Models\Recipe;
use Illuminate\View\View;
use App\Services\OpenAIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    /**
     * Show the recipes.
     * @return View
     */
    public function index(Request $request): View
    {
        $client = OpenAIService::getClient();

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

        $recipes_response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Only return the filtered list found as a comma-separated list. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks.'],
                ['role' => 'user', 'content' => $recipes_prompt],
            ],
        ]);

        $recipes = $recipes_response['choices'][0]['message']['content'];
        $recipes = explode(',', $recipes);
        $recipes = Recipe::whereIn('name', Arr::map($recipes, fn($value) => trim($value)))->paginate(9);

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
        $userHasFavorited = Auth::user() && Auth::user()->favoriteRecipes->contains('id', $recipe->id);
        $notations = Notation::where('recipe_id', $recipe->id)->orderBy('created_at', 'desc')->take(5)->get();

        $openAIService = new OpenAiService();
        
        $ingredients = $openAIService->getIngredients($recipe);
        $related_recipes = $openAIService->getRelatedRecipes($recipe);

        if ($related_recipes->count() == 0) {
            $related_recipes = Recipe::inRandomOrder()->take(4)->get();
        }

        return view('recipes.show', [
            'recipe' => $recipe,
            'notations' => $notations,
            'userHasNotated' => $userHasNotated,
            'userHasFavorited' => $userHasFavorited,
            'related_recipes' => $related_recipes,
        ]);
    }

    /**
     * Favorite the recipe.
     * @param Recipe $recipe
     * @return View
     */
    public function addFavorite(Request $request): RedirectResponse
    {
        Auth::user()->favoriteRecipes()->toggle($request->recipe_id);
        return redirect()->back();
    }

    public function favorites(): View
    {
        $recipes = Auth::user()->favoriteRecipes()->paginate(9);
        return view('recipes.favorites', ['recipes' => $recipes]);
    }
}
