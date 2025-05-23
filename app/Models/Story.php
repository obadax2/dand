<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'genre',
        'status', // e.g., 'draft', 'published'
    ];

    /**
     * Get the user that owns the story.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the characters for the story.
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

   
     public function map()
     {
         return $this->hasOne(Map::class); 
     }
     public function blog()
    {
        return $this->hasOne(Blog::class);
    }
}