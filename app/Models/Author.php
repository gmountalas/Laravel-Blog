<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    // Eloquent Relationship between Author and Profile (1-to-1)
    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }
}
