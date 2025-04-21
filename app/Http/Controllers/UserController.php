<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Check if the authenticated user is an HR
        if (!Auth::check() || !Auth::user()->isHR()) {
            abort(403, 'Unauthorized action.');
        }

        $search = $request->query('search');
        
        // If a search term is provided, filter users based on the search term.
        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                          ->orWhere('username', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%")
                          ->get();
        } else {
            // Otherwise, retrieve all users.
            $users = User::all();
        }
        
        return view('users.index', compact('users'));
    }

    /**
     * Update the specified user's role.
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

        // Update the user's role
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }
    public function destroy(User $user)
{
    // Check if the authenticated user is an HR
    if (!Auth::check() || !Auth::user()->isHR()) {
        abort(403, 'Unauthorized action.');
    }

    // Ensure the user being deleted is an admin
    if ($user->role !== 'admin') {
        abort(403, 'Unauthorized action. You can only delete admin users.');
    }

    // Delete the user
    $user->delete();

    return redirect()->route('users.index')->with('success', 'Admin deleted successfully.');
}
}