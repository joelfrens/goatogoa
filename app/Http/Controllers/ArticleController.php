<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{	
    
    /**
     * Articles constructor
     * Invokes Auth middleware
     */
	public function __contruct()
    {
		$this->middleware('auth');
	}
	
    /**
     * Lists all articles
     *
     * @param Request $Request
     *  
     * @return Array $artcles
     */
    public function index(Request $request)
    {   

        // Get users articles
        $articles = \App\Article::where('user_id', $request->user()->id)->orderBy('id', 'desc')->paginate(5);

        // Get all categories
        $categories = \App\Category::lists('name','id');

        // Get all tags
        $tags = \App\Tag::lists('name','id');

        // $articles = DB::table('articles')->get(); <- just for reference
        // Send array of articles to the view
        return view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'tags' => $tags
        ]);

    }

    /**
     * Add the article in the database
     *
     * @param Request $request
     *
     * @return Array $articles
     */
    public function add(Request $request)
    {

        // Validate the inputs
        // title and content inputs are required
        $this->validate($request, [
            "title" => 'required',
            "content" => 'required'
        ]);

        // Add the article
        // $request->user()->articles adds the user Id in the database
        $article = $request->user()->article()->create([
            'title' => $request->title,
            'content' => $request->content,
            'what_you_can_do' => $request->what_you_can_do,
            'xcoordinate' => $request->xcoordinates,
            'ycoordinate' => $request->ycoordinates,
            'category_id' => $request->category,
            'active' => $request->status
        ]);
        
        // update tags
        foreach ($request->tags as $key => $val){
            $t = new \App\article_tags(array(
                'article_id' => $article->id,
                'tag_id' => $key
            ));

            // Perform save query
            $t_save = $article->article_tags()->save($t);
        }

        // Set destination path for the image uploads
        $destinationPath = 'uploads';
            
        // Multiple images input
        $files = \Input::file('image');
    
        // Upload images
        $id = $article->id;
        $uploaded = $this->uploadImages($files,$id,$article);

        // Session flash message
        $request->session()->flash('alert-success', 'Article has been updated!');

        // Set the success message
        $request->session()->flash('alert-success', 'Article has been added!');

        // Redirect to the articles page
        return redirect('/admin/articles');

    }

    /**
     * Edit article
     *
     * @param integer $id [article Id]
     *
     * @return array $article [article details]
     */
    public function edit($id)
    {   

        // Get article details based on the Id
        $article = \App\Article::findORFail($id);

        // Get images
        $images = \App\article_images::where('article_id', $id)->get();
        //dd($article);

        // Get all categories
        $categories = \App\Category::lists('name','id');

        // Get all tags
        $tags = \App\Tag::lists('name','id');

        //Return the article details to the view
        return view('articles.edit')->with('article',$article)
                                    ->with('images',$images)
                                    ->with('categories',$categories)
                                    ->with('tags',$tags);

    }

    /**
     * Updates articles
     *
     * @param int $id [article ID]
     * @param Request $request [All article information]
     *
     * @return Redirects to articles page
     */
    public function update($id, 
                           Request $request)
    {   
        
        // Get article details
        $article = \App\Article::findORFail($id);

        // Validations
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ]);

        // Request all inputs
        $input = $request->all();
        //dd($input);
        // Save the input
        $article->fill($input)->save();

        // Multiple images input
        $files = \Input::file('image');

        //upload Images
        $uploaded = $this->uploadImages($files,$id,$article);

        // Session flash message
        $request->session()->flash('alert-success', 'Article has been updated!');

        // Redirect to the articles page
        return redirect('/admin/articles');
        
    }

    /**
     * @param Request $request [All article information]
     *
     * @param Article $article [Article Id]
     *
     * @return Redirects to articles page 
     */
    public function destroy(Request $request,
                            \App\Article $article){

        // Authorisation to make sure that the owner is deleting the article
        $this->authorize('destroy', $article);

        // Delete article
        $article->delete();

        // Success delete message
        $request->session()->flash('alert-success', 'Article has been deleted!');

        // Redirect to articles page
        return redirect('/admin/articles');

    }

    /**
     * Upload images to the 'uploads' folder
     * @param Array $files
     * @param Integer $id
     * @return Boolean true/false 
     */
    public function uploadImages($files,
                                 $id,
                                 $article)
    {   

        // Set destination path for the image uploads
        $destinationPath = 'uploads';

        // Images count
        $file_count = sizeof($files);

        // rules for image array input
        //'required|mimes:png,gif,jpeg,txt,pdf,doc'
        $rules = array('file' => 'required'); 
        
        # Process all the images array
        foreach($files as $file) {
            
            // Validate image to be only png,gif,jpeg,txt,pdf,doc
            $validator = \Validator::make(array('file'=> $file), $rules);
            
            // Check for validation
            if($validator->passes()){

                // set filname
                $filename = $file->getClientOriginalName();

                // upload the files to the destination folder
                $upload_success = $file->move($destinationPath, $filename);

                // Add images
                $image = new \App\article_images(array(
                    'article_id' => $id,
                    'image' => $filename
                ));

                // Perform save query
                $image = $article->article_images()->save($image);

                // return success
                if($image)
                    return true;

            }
        
        }
    }

}
