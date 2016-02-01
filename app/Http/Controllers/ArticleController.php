<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{	

	public function __contruct(){
		$this->middleware('auth');
	}
	
    //
    public function index(){
    	return view('articles.index');
    }

    public function add_new(Request $request){

    }

    public function delete_article()
    {
    	# code...
    }
}
