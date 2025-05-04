<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'story_id',
        'name',
        'description',
        'image_url',
    ];

    /**
     * Get the story that the character belongs to.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}