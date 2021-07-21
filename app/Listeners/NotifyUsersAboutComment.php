<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUnsersPostWasCommented;
use App\Jobs\ThrottleMail;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersAboutComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        ThrottleMail::dispatch(
            new CommentPostedMarkdown($event->comment), 
            $event->comment->commentable->user
        )->onQueue('high');

        // Run/Dispatch a job
        NotifyUnsersPostWasCommented::dispatch($event->comment)
            ->onQueue('low');
    }
}
