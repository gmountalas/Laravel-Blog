<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostsController extends Controller
{

    private $posts = [
        1 => [
            'title' => 'Intro to Laravel',
            'content' => 'This is a short intro to Laravel',
            'is_new' => true,
            'has_comments' => true
        ],
        2 => [
            'title' => 'Intro to PHP',
            'content' => 'This is a short intro to PHP',
            'is_new' => false
        ],
        3 => [
            'title' => 'Intro to Java',
            'content' => 'This is a short intro to PHP',
            'is_new' => false
        ]
    ];

    public function __construct()
    {
        $this->middleware('auth')
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::enableQueryLog();
        // $posts = BlogPost::all(); // Lazy Loading
        // $posts = BlogPost::with('comments')->get(); // Eager Loading

        // foreach ($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        // Replace BlogPost::all() with withCount('comments')->get()
        // comments is the name of the Eloquent Relation in BlogPost Model
        // it will return a new property comments_count (relationshipName_count)
        // which will contain the number of related models/instances for a particular BlogPost.
        return view(
            'posts.index', 
            ['posts' => BlogPost::withCount('comments')->get()]
        );
        // return view('posts.index', ['posts' => BlogPost::orderBy('created_at', 'desc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Replaced Request class with custom StorePost class for custom validation
    // public function store(Request $request)
    public function store(StorePost $request)
    
    {
        // Replaced validation rules with custom validation form StorePost
        // $request->validate([
        //     'title' => 'bail|required|min:5|max:100',
        //     'content' => 'required|min:10'
        // ]); 
        // $post = new BlogPost();
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');
        // $post->save();

        $validated = $request->validated();
        
        // Replaced with Model Mass Assignment
        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();
        
        $post= Blogpost::create($validated);

        $request->session()->flash('status', 'The blog post was created!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($this->posts[$id]), 404);
        // return view('posts.show', ['post' => $this->posts[$id]]);

        // Simplified of the above
        // Pass a BlogPost along with it's comments to the show view
        return view('posts.show', ['post' => BlogPost::with('comments')->findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if a user (automatically passed from Laravel) can
        // edit a post
        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post");
        // }

        // Replace the above Gate
        $this->authorize('update-post', $post);

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if a user (automatically passed from Laravel) can
        // update a post
        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post");
        // }

        // Replace the above Gate
        $this->authorize('posts.update', $post);

        $validated = $request->validated();
        $post->fill($validated);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if a user (automatically passed from Laravel) can
        // delete a post
        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't delete this blog post");
        // }

        // Replace the above Gate
        $this->authorize('posts.delete', $post);
        $post->delete();

        session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
