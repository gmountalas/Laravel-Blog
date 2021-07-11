<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'blog_post_id'];

    // Eloquent relationship 1-to-1 with BlogPost model
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function url()
    {
        return Storage::url($this->path);
    }
}
