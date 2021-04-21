<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Eloquent Relationship between Profile and Author (1-to-1)
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }
}
