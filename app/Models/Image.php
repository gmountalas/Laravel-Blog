<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    // remove blog_post_id because of change to 1-to-1 polymorphic relation
    protected $fillable = ['path' /*,'blog_post_id'*/];

    // Eloquent relationship 1-to-1 with BlogPost model: blogPost
    // Eloquent relationship 1-to-1 polymorphic with BlogPost model: imageable
    public function imageable()
    {
        // return $this->belongsTo(BlogPost::class);
        return $this->morphTo(BlogPost::class);
    }

    public function url()
    {
        return Storage::url($this->path);
    }
}
