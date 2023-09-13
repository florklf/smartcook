<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\View\View;

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
        return view('recipes.show', ['recipe' => $recipe]);
    }
}
