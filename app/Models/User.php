<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; 

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
        return $this->friends()->wherePivot('status', 'accepted');
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
 public function followers()
{
    return $this->hasMany(Follower::class, 'user_id');
}

// Users this user is following
public function following()
{
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
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
}