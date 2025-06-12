<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'price',
        'visibility',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}
public function upvotes()
{
    return $this->hasMany(Upvote::class);
}

public function weightedUpvotes()
{
    return $this->upvotes->sum(function ($upvote) {
        $friendCount = $upvote->user->acceptedFriends()->count();
        return 1 + $friendCount * 0.1; // Adjust weight factor here
    });
}
public function isUpvotedBy(User $user)
{
    return $this->upvotes()->where('user_id', $user->id)->exists();
}

public function downvotes()
{
    return $this->hasMany(Downvote::class);
}
public function score()
{
    return $this->upvotes->count() - $this->downvotes->count();
}
public function weightedDownvotes()
{
    return $this->downvotes->sum(function ($downvote) {
        $friendCount = $downvote->user->acceptedFriends()->count();
        $weight = 1 / (1 + $friendCount * 0.1); // reduce impact based on number of friends
        return $weight;
    });

}
}