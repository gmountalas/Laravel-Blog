<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory, Taggable;

    // Instead of deleting the record from the database, set a field on that
    // model called deletedAt and Laravel will know the record was soft deleted
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];
    
    // Eloquent 1-to-many Polymorphic relation 
    public function commentable()
    {
        return $this->morphTo();
    }

    // // Eloquent 1-to-many relation with BlogPost, replace by above Polymorphic 1-to-many
    // public function blogPost()
    // {
    //     // return $this->belongsTo('App\Models\BlogPost', 'post_id', 'blog_post_id');
    //     return $this->belongsTo('App\Models\BlogPost');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Removed to use from Taggable trait
    // // Eloquent many-to-many Polymorphic relation with Tag model
    // public function tags()
    // {
    //     return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    // }

    public static function boot() 
    {
        parent::boot();

        static::creating(function (Comment $comment) {
            // Reset the BlogPost cache when a new comment is added.
            // For the 1-to-many relation, replace with below Polymorphic
            // Cache::tags(['blog-post'])->forget("blog-post-{$comment->blog_post_id}");
            
            // For polymorphic 1-to-many Check if the comment is for a blogPost before reseting cache
            // The if is not necessary for a regular 1-to-many
            if ($comment->commentable_type === BlogPost::class) {
                Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
                Cache::tags(['blog-post'])->forget("mostCommented");
            }
        });

        // static::addGlobalScope(new LatestScope);

    }

    public function scopeNewest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}
