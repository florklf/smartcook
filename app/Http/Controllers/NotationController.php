<?php

namespace App\Http\Controllers;

use App\Models\Notation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotationController extends Controller
{
  /**
    * Show the notations.
    * @return View
    */
  public function index(): View
  {
    $notations = Notation::all();
    return view('home', ['notations' => $notations]);
  }
  
  /**
    * Store a new notation.
    * @return View
    */
  public function store(Request $request): RedirectResponse
  {
    $notation = new Notation();
    $notation->user_id = auth()->user()->id;
    $notation->recipe_id = $request->recipe_id;
    $notation->notation = $request->notation;
    $notation->comment = $request->comment;
    $notation->save();
    return redirect()->back()->with('success', 'Votre notation a bien été enregistrée.');
  }
}
