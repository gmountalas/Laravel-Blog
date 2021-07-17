<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, StoreComment $request)
    {
        // commentsOn: Polymorphic 1-to-many Eloquent relation
        $user->commentsOn()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back()
            ->withStatus('Comment was created!');
    }
}
