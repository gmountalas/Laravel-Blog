<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    protected $fillable = ['title', 'content'];

    use HasFactory;

    // Instead of deleting the record from the database, set a field on that
    // model called deletedAt and Laravel will know the record was soft deleted
    use SoftDeletes;

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public static function boot() 
    {
        parent::boot();

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
