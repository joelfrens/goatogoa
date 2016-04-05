<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    /**
     * Categories constructor
     * Invokes Auth middleware
     */
	public function __contruct()
    {
		$this->middleware('auth');
	}

	/**
     * Lists all categories
     *
     * @param Request $request
     *
     * @return Array $artcles
     */
    public function index(Request $request)
    {   

        // Get categories
        $categories = DB::table('categories')->paginate(5); 
        
        // Send array of categories to the view
        return view('categories.index', [
            'categories' => $categories,
        ]);

    }

    /**
     * Add the Category in the database
     *
     * @param Request $request
     *
     * @return Array $categories
     */
    public function add(Request $request)
    {

        // Validate the inputs
        // title and content inputs are required
        $this->validate($request, [
            "name" => 'required'
        ]);

        // Save the category
        $category = new \App\Category;
        $category->name = $request->name;
        $category->save();
        
        // Set the success message
        $request->session()->flash('alert-success', 'Category has been added!');

        // Redirect to the categories page
        return redirect('/admin/categories');

    }

    /**
     * Edit Category
     * @param integer $id [category Id]
     *
     * @return array $category [cateogry details]
     */
    public function edit($id)
    {   

        // Get category details based on the Id
        $category = \App\Category::findORFail($id);

        //Return the category details to the view
        return view('categories.edit')->with('category',$category);

    }

    /**
     * Updates categorys
     * @param int $id [category ID]
     * @param Request $request [All category information]
     *
     * @return Redirects to categories page
     */
    public function update($id, 
                           Request $request)
    {   
        
        // Get category details
        $category = \App\Category::findORFail($id);

        // Validations
        $this->validate($request, [
            'name' => 'required'
        ]);

        // Request all inputs
        $input = $request->all();

        // Update the record
        $category->fill($input)->save();

        // Session flash message
        $request->session()->flash('alert-success', 'Category has been updated!');

        // Redirect to the categories page
        return redirect('/admin/categories');
        
    }

    /**
     * @param Request $request [All category information]
     * @param Category $category [Category Id]
     *
     * @return Redirects to categorys page 
     */
    public function destroy(Request $request, 
                            \App\Category $category){

        // Delete category
        $category->delete();

        // Success delete message
        $request->session()->flash('alert-success', 'Category has been deleted!');

        // Redirect to categories page
        return redirect('/admin/categories');

    }

}
