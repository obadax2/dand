<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Assuming maps can also be associated with users
        'title',
        'image', // Assuming this is a file path or URL
        'description',
       
        'story_id',
    ];

    /**
     * Get the user that owns the map.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function story()
     {
        return $this->belongsTo(Story::class);
    }
}