<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];

    use HasFactory;

    // Instead of deleting the record from the database, set a field on that
    // model called deletedAt and Laravel will know the record was soft deleted
    use SoftDeletes;

    public function comments()
    {
        // Eloquent 1-to-many Polymorphic relation with Comment model
        return $this->morphMany(Comment::class, 'commentable')->newest();

        // Eloquent 1-to-many relation with Comment, replace with Polymorphic
        // return $this->hasMany('App\Models\Comment')->newest();
    }

    // Eloquent relationship 1-to-many with User Model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Eloquent relationship many-to-many with Tag model
    public function tags()
    {
        // Eloquent many-to-many Polymorphic relation with Tag model
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();

        // Eloquent many-to-many relation with Tag, replace with Polymorphic
        // return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // Eloquent relationship 1-to-1 with Image model: hasOne
    // Eloquent relationship 1-to-1 Polymorphic with Image model: morphOne
    public function image()
    {
        // return $this->hasOne(Image::class);
        return $this->morphOne(Image::class, 'imageable');
    }

    public function scopeNewest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeNewestWithRelations(Builder $query)
    {
        return $query->newest()
            ->withCount('comments')
            ->with(['user', 'tags']);
    }

    public static function boot() 
    {
        // Must add this specifi Global query scope before the boot method
        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();

        // static::addGlobalScope(new LatestScope);

        // Soft delete the child relations (comments) of the BlogPost Model
        // when a BlogPost instance is deleted
        static::deleting(function (BlogPost $blogPost) {
            $blogPost->comments()->delete();
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        static::updating(function (BlogPost $blogPost) {
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        // Restore from soft deletion the child relation (comments) of the
        // BlogPost Model when a BlogPost instance is restored
        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }

}
