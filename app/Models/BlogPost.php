<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];

    use HasFactory;

    // Instead of deleting the record from the database, set a field on that
    // model called deletedAt and Laravel will know the record was soft deleted
    use SoftDeletes;

    public function comments()
    {
        return $this->hasMany('App\Models\Comment')->newest();
    }

    // Eloquent relationship 1-to-many with User Model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNewest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot() 
    {
        parent::boot();

        static::addGlobalScope(new LatestScope);

        // Soft delete the child relations (comments) of the BlogPost Model
        // when a BlogPost instance is deleted
        static::deleting(function (BlogPost $blogPost) {
            $blogPost->comments()->delete();
        });

        // Restore from soft deletion the child relation (comments) of the
        // BlogPost Model when a BlogPost instance is restored
        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }

}
