<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Article;
use App\Category;
use App\Tag;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    
    Route::auth();

    Route::group(['prefix' => 'admin'], function(){
	    
	    Route::get('/home', function(Request $request){
	    	return view('home');
	    });
	    
	    // Implements middlewate Auth
	    Route::group(['middleware' => ['auth']],function(){

	    	// Articles Routes
	    	Route::get('/articles', 'ArticleController@index');
		    Route::post('/article', [
		    	'as'=>'article.add',
		    	'uses' => 'ArticleController@add'
		    ]);
		    Route::get('/article/edit/{id}', 'ArticleController@edit');
		    Route::get('/article/edit/{id}',[
			    'as' => 'article.edit',
			    'uses' => 'ArticleController@edit'
			]);
		    Route::PATCH('/article/update/{id}', [ 
		    	'as' => 'article.update', 
		    	'uses' => 'ArticleController@update'
		    ]);
		    Route::delete('/article/{article}', 'ArticleController@destroy');

		    // Recipes Routes
		    Route::get('/recipes','RecipeController@index');
		    Route::post('/recipe', [
		    	'as'=>'recipes.add',
		    	'uses'=>'RecipeController@add'
		    ]);
		    Route::get('/recipe/edit/{id}', [
		    	'as'=>'recipe.edit',
		    	'uses'=>'RecipeController@edit'
		    ]);
		    Route::patch('/recipe/update/{id}', [
		    	'as'=>'recipe.update',
		    	'uses'=>'RecipeController@update'
		    ]);
		    Route::delete('/recipe/{recipe}','RecipeController@destroy');

		    // Articles Category routes
		    Route::get('/categories', [
		    	'as'=>'category.add',
		    	'uses' => 'CategoryController@index'
		    ]);
		    Route::post('/category', 'CategoryController@add');
		    Route::get('/category/edit/{id}', [
			    'as' => 'category.edit',
			    'uses' => 'CategoryController@edit'
			]);
		    Route::PATCH('/category/update/{id}', [ 
		    	'as' => 'category.update', 
		    	'uses' => 'CategoryController@update'
		    ]);
		    Route::delete('/category/{category}', 'CategoryController@destroy');

		    // Tag routes
		    Route::get('/tags', [
		    	'as'=>'tag.add',
		    	'uses' => 'TagController@index'
		    ]);
		    Route::post('/tag', 'TagController@add');
		    Route::get('/tag/edit/{id}', [
			    'as' => 'tag.edit',
			    'uses' => 'TagController@edit'
			]);
		    Route::PATCH('/tag/update/{id}', [ 
		    	'as' => 'tag.update', 
		    	'uses' => 'TagController@update'
		    ]);
		    Route::delete('/tag/{tag}', 'TagController@destroy');

		});
	});


});
