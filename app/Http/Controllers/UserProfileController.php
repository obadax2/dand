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

        $friendRequests = $user->friendRequests()
            ->withPivot('status')
            ->wherePivot('status', 'pending')
            ->get();

        $followersCount = $user->followers()->count();

        $friends = $user->friends();
        $friendsCount = $user->allFriends()->count();

        $senderUser = $user;

        return view('userprofile', compact('user', 'friendRequests', 'followersCount', 'friendsCount', 'senderUser'));
    }
    public function updateProfile(Request $request)
    {


        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('pictures', 'public');
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
        $request->status = 'accepted';
        $request->save();


        $user1 = User::find($request->from_user_id);
        $user2 = User::find($request->to_user_id);
        $user1->friends()->attach($user2->id, ['status' => 'accepted']);

        return back()->with('success', 'Friend request accepted.');
    }
}
