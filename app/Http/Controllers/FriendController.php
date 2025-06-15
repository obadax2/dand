<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FriendController extends Controller
{
 public function sendRequest(Request $request, User $user)
{
    $currentUser = auth()->user();

    if ($currentUser->id === $user->id) {
        return response()->json(['error' => 'You cannot send a friend request to yourself.'], 400);
    }

    $exists = \DB::table('friends')
        ->where(function ($query) use ($currentUser, $user) {
            $query->where('user1_id', $currentUser->id)
                  ->where('user2_id', $user->id);
        })
        ->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('user2_id', $currentUser->id)
                  ->where('user1_id', $user->id);
        })
        ->exists();

    if ($exists) {
        return response()->json(['error' => 'Friend request already sent or you are already friends.'], 400);
    }

    \DB::table('friends')->insert([
        'user1_id' => $currentUser->id,
        'user2_id' => $user->id,
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => 'Friend request sent.']);
}

public function acceptRequest($senderUserId)
{
    $currentUser = auth()->user();
    $senderUser = \App\Models\User::findOrFail($senderUserId);

    $friendRequest = $currentUser->friendRequests()
        ->where('user1_id', $senderUser->id)
        ->where('status', 'pending')
        ->first();

    if (!$friendRequest || !$friendRequest->pivot) {
        return back()->with('error', 'No friend request from this user.');
    }

    $friendRequest->pivot->status = 'accepted';
    $friendRequest->pivot->save();

    if (!$senderUser->friends()->where('user2_id', $currentUser->id)->where('status', 'accepted')->exists()) {
        $senderUser->friends()->attach($currentUser->id, ['status' => 'accepted']);
    }

    return back()->with('success', 'Friend request accepted.');
}
    public function rejectRequest(Request $request, User $user)
{
    $currentUser = auth()->user();

    if ($currentUser->friendRequests()->where('user1_id', $user->id)->exists()) {
        $currentUser->friendRequests()->detach($user->id);
        return back()->with('success', 'Friend request rejected.');
    }

    return back()->with('error', 'No friend request from this user.');
}
}
