<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Show the application dashboard.
     * @return View
     */
    public function index(): View
    {
        // $recipes = Auth::user()->recipes()->latest()->get();
        $recipes = [
            (object) [
                'title' => 'Recette 1',
                'description' => 'Description de la recette 1',
            ],
            (object) [
                'title' => 'Recette 2',
                'description' => 'Description de la recette 2',
            ],
            (object) [
                'title' => 'Recette 3',
                'description' => 'Description de la recette 3',
            ],
            (object) [
                'title' => 'Recette 4',
                'description' => 'Description de la recette 4',
            ]
        ];
        return view('home', compact('recipes'));
    }
}
