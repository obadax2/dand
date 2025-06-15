<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isHR()) {
            abort(403, 'Unauthorized action.');
        }

        $search = $request->query('search');

        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $users = User::all();
        }

        return view('users.index', compact('users'));
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     */
    public function updateRole(Request $request, User $user)
    {

        if (!Auth::check() || !Auth::user()->isHR()) {
            abort(403, 'Unauthorized action.');
        }


        $request->validate([
            'role' => 'required|string|in:admin,user,hr',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }
    public function destroy(User $user)
    {
        if (!Auth::check() || !Auth::user()->isHR()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized action. You can only delete admin users.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Admin deleted successfully.');
    }
    public function ban(User $user)
    {
        $user->banned = !$user->banned;
        $user->save();

        return redirect()->route('users.index')->with('success', $user->banned ? 'User banned successfully!' : 'User unbanned successfully!');
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "%$query%")
            ->orWhere('username', 'LIKE', "%$query%")
            ->limit(10)
            ->get(['id', 'name', 'username']);
        return response()->json($users);
    }

    public function removeProfilePicture()
    {
        $user = Auth::user();

        // Just set profile_picture to NULL in the database
        $user->update(['profile_picture' => null]);
        return response()->json(['success' => true]);
    }
}
