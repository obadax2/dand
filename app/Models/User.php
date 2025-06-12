<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'dob_day',
        'dob_month',
        'dob_year',
        'banned',
        'email_verified_at',
       
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user has the 'admin' role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has the 'hr' role.
     */
    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    /**
     * Check if the user has the 'user' role.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the stories for the user.
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);    
    }
    public function friends()
{
    // Friends where this user is user1
    $friendsAsUser1 = $this->belongsToMany(User::class, 'friends', 'user1_id', 'user2_id')
        ->withPivot('status')
        ->wherePivot('status', 'accepted');

    // Friends where this user is user2
    $friendsAsUser2 = $this->belongsToMany(User::class, 'friends', 'user2_id', 'user1_id')
        ->withPivot('status')
        ->wherePivot('status', 'accepted');

    // Merge both collections
    return $friendsAsUser1->union($friendsAsUser2);
}
    /**
     * Get the list of friends with accepted status.
     */
  public function acceptedFriends()
{
    $friendsAsUser1 = DB::table('friends')
        ->where('user1_id', $this->id)
        ->where('status', 'accepted')
        ->pluck('user2_id');

    $friendsAsUser2 = DB::table('friends')
        ->where('user2_id', $this->id)
        ->where('status', 'accepted')
        ->pluck('user1_id');

    $friendIds = $friendsAsUser1->merge($friendsAsUser2)->unique();

    return User::whereIn('id', $friendIds)->get();
}


    /**
     * Get the list of friend requests received.
     */
    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'user2_id', 'user1_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    /**
     * Get the followers of this user.
     */
// Users this user follows
public function following()
{
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
}

// Users who follow this user
public function followers()
{
    return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
}

public function allFriends()
{
    $friendIds = array_merge(
        \DB::table('friends')->where('user1_id', $this->id)->where('status', 'accepted')->pluck('user2_id')->toArray(),
        \DB::table('friends')->where('user2_id', $this->id)->where('status', 'accepted')->pluck('user1_id')->toArray()
    );

    return User::whereIn('id', $friendIds)->get();
}
public function cartItems()
{
    return $this->hasMany(\App\Models\Cart::class);
}
public function conversations()
{
    return $this->hasMany(Conversation::class);
}
public function reviews()
{
    return $this->hasMany(Review::class);
}
public function allFriendsAsUser1()
{
    return $this->belongsToMany(User::class, 'friends', 'user1_id', 'user2_id')
        ->withPivot('status');
}

// All friendships where this user is user2 (any status)
public function allFriendsAsUser2()
{
    return $this->belongsToMany(User::class, 'friends', 'user2_id', 'user1_id')
        ->withPivot('status');
}

// Get all friendships regardless of status

// Friend requests sent by this user (status pending)
public function sentFriendRequests()
{
    return $this->allFriendsAsUser1()->wherePivot('status', 'pending');
}


}