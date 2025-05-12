<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FriendController extends Controller
{
    public function sendRequest(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Check if request already exists
        if ($currentUser->friends()->where('user2_id', $user->id)->exists()) {
            return back()->with('error', 'Friend request already sent or you are already friends.');
        }

        // Send request with 'pending' status
        $currentUser->friends()->attach($user->id, ['status' => 'pending']);

        return back()->with('success', 'Friend request sent.');
    }

 public function acceptRequest($senderUserId)
{
    $currentUser = auth()->user();
    $senderUser = \App\Models\User::findOrFail($senderUserId);
    
    // Check if a pending request exists from senderUser to currentUser
    $friendRequest = $currentUser->friendRequests()
        ->where('user1_id', $senderUser->id)
        ->first();

    if (!$friendRequest || !$friendRequest->pivot) {
        return back()->with('error', 'No friend request from this user.');
    }

    // Update the status to 'accepted'
    $friendRequest->pivot->status = 'accepted';
    $friendRequest->pivot->save();

    // Create reciprocal friendship if not exists
    if (!$senderUser->friends()->where('user2_id', $currentUser->id)->exists()) {
        $senderUser->friends()->attach($currentUser->id, ['status' => 'accepted']);
    }

    return back()->with('success', 'Friend request accepted.');
}

    public function rejectRequest(Request $request, User $user)
    {
        $currentUser = auth()->user();

        $friendRequest = $currentUser->friendRequests()->where('user1_id', $user->id)->first();

        if ($friendRequest) {
            $friendRequest->pivot->delete();

            return back()->with('success', 'Friend request rejected.');
        }

        return back()->with('error', 'No friend request from this user.');
    }
}