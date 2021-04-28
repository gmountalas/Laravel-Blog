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
}
