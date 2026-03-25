<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_name',
        'logo',
        'poster',
        'thumbnail',
        'rating',
        'synopsis',
        'language',
        'country',
        'length',
        'release_date',
        'end_date',
        'age_restricted',
        'trailer',
    ];

    // Relationships
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_movies', 'movie_id', 'actor_id');
    }

    public function directors()
    {
        return $this->belongsToMany(Director::class, 'director_movies', 'movie_id', 'director_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_movies', 'movie_id', 'category_id');
    }
}
