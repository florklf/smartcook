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
        $openAIService = new OpenAiService();

        $recipes = $openAIService->getRecipes($request);

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
