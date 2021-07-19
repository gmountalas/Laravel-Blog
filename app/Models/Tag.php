<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Eloquent relation many-to-many with BlogPost
    public function blogPosts()
    {
        // Regular many-to-many Eloquent relation with BlogPost
        // return $this->belongsToMany(BlogPost::class)->withTimestamps();

        //  Polymorphic many-to-many Eloquent relation with BlogPost
        return $this->morphedByMany(BlogPost::class, 'taggable')->withTimestamps();
    }

    //  Polymorphic many-to-many Eloquent relation with Comment
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'taggable')->withTimestamps();
    }
}
