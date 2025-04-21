<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.register');
    }

    // Handle login request
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Authentication passed, redirect to intended page
            return redirect()->intended('home'); // Change 'home' to your desired path
        }

        // Authentication failed, redirect back
        return redirect()->back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/'); // Redirect to your desired path after logout
    }
}