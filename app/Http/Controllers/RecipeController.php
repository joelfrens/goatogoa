<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    
	/**
	 * Implement Auth for all actions
	 */

	public function __construct(){

		// Activate auth component
		$this->middleware('auth');

	}

	/**
	 * Listing action for all recipes
	 * @param Request $request
	 *  
	 * @return Array $articles
	 */
	public function index(Request $request)
	{	

		// Fetch all recipes created by the user
		$recipes = \App\Recipe::where('user_id', $request->user()->id)->paginate(5);

		// View the recipes
		return view('recipes.index',[
			'recipes' => $recipes
		]);

	}



}
