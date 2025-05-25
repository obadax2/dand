<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Fetch friend requests with pivot status
        $friendRequests = $user->friendRequests()
            ->withPivot('status')
            ->wherePivot('status', 'pending')
            ->get();

        // Count followers
        $followersCount = $user->followers()->count();

        // Fetch friends from both relations and merge
        $friends = $user->friends();
        $friendsCount = $user->allFriends()->count();

        // Define senderUser (assuming it's the current user or specify as needed)
        $senderUser = $user; // or fetch another user as per your logic

        // Pass all variables to the view
        return view('userprofile', compact('user', 'friendRequests', 'followersCount', 'friendsCount', 'senderUser'));
    }
    public function updateProfile(Request $request)
    {


        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Store new picture in 'public/pictures'
            $path = $request->file('profile_picture')->store('pictures', 'public');


            // Save path relative to 'public/'
            $relativePath = str_replace('public/', '', $path);
            $user->profile_picture = $relativePath;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated.');
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

    public function acceptFriendRequest($requestId)
    {
        $request = \App\Models\FriendRequest::findOrFail($requestId);
        if ($request->to_user_id !== auth()->id()) {
            abort(403);
        }
        // Update status to accepted
        $request->status = 'accepted';
        $request->save();


        $user1 = User::find($request->from_user_id);
        $user2 = User::find($request->to_user_id);
        $user1->friends()->attach($user2->id, ['status' => 'accepted']);

        return back()->with('success', 'Friend request accepted.');
    }
}
