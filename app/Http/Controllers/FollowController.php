<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $currentUser = auth()->user();

        // Prevent following yourself
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Check if already following
        if ($currentUser->following()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Already following this user.');
        }

        // Attach follow
        $currentUser->following()->attach($user->id);

        return back()->with('success', 'Now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        $currentUser = auth()->user();

        $currentUser->following()->detach($user->id);

        return back()->with('success', 'Unfollowed ' . $user->name);
    }
}
