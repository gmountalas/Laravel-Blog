<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as EventsCommentPosted;
use App\Http\Requests\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Jobs\NotifyUnsersPostWasCommented;
use App\Jobs\ThrottleMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlogPost $post)
    {
        // return new CommentResource($post->comments->first());
        return CommentResource::collection($post->comments()->with('user')->get());
        return $post->comments()->with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        // $request->session()->flash('status', 'Comment was created!');

        // return redirect()->back();

        // Send mail immediately
        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );

        
        // Queue the sending of the email
        // Mail::to($post->user)->queue(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );

        // To delay the sending of the email
        // $when = now()->addMinutes(1);
         // Queue the sending of the email
        //  Mail::to($post->user)->later(
        //      $when,
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );

        event(new EventsCommentPosted($comment));

        // Replaced by the aboce even
        // ThrottleMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
        //     ->onQueue('high');

        // // Run/Dispatch a job
        // NotifyUnsersPostWasCommented::dispatch($comment)
        //     ->onQueue('low');
        
        return redirect()->back()
            ->withStatus('Comment was created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
