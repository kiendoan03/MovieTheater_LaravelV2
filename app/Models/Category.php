<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relationships
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'category_movies', 'category_id', 'movie_id');
    }
}
