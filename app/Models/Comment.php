<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;

    // Instead of deleting the record from the database, set a field on that
    // model called deletedAt and Laravel will know the record was soft deleted
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];
    
    public function blogPost()
    {
        // return $this->belongsTo('App\Models\BlogPost', 'post_id', 'blog_post_id');
        return $this->belongsTo('App\Models\BlogPost');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot() 
    {
        parent::boot();

        static::creating(function (Comment $comment) {
            Cache::forget("blog-post-{$comment->blog_post_id}");
            Cache::forget("mostCommented");
        });

        // static::addGlobalScope(new LatestScope);

    }

    public function scopeNewest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}
