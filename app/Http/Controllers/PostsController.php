<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{

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
        // Replace BlogPost::all() with withCount('comments')->get()
        // comments is the name of the Eloquent Relation in BlogPost Model
        // it will return a new property comments_count (relationshipName_count)
        // which will contain the number of related models/instances for a particular BlogPost.
        return view(
            'posts.index', [
                'posts' => BlogPost::newestWithRelations()->get(),
            ]
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
        $validated['user_id'] = $request->user()->id;

        // Replaced with Model Mass Assignment
        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();
        
        $post= Blogpost::create($validated);
        
        // Check if user has uploaded a file, on the input name="thumbnail"
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(
                Image::create(['path' => $path])
            );
        }

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
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function ($query) {
        //         return $query->newest();
        //     }])->findOrFail($id)
        // ]);

        // Store in cache a blogPost, be sure to use event in booted method of
        // the Model to uncache it when changed. 
        $blogPost = Cache::tags(['blog-post'])->remember(`blog-post-{$id}`, 60, function () use ($id){
            return BlogPost::with('comments', 'tags', 'user', 'comments.user')
            ->findOrFail($id);
        });

        // Implement a counter for how many people are currently on o blogPost
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference -= 1;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // Check if current user was in the list of users that are on a
        // blogPost, the list is fetched from the cache
        if (!array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference += 1;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);;

        return view('posts.show', [
            'post' => $blogPost,
            'counter' =>$counter,
        ]);
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
        $this->authorize('update', $post);

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
        $this->authorize('update', $post);

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
        $this->authorize('delete', $post);
        $post->delete();

        session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
