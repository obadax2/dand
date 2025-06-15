<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        Log::info('AdminController@index reached successfully.');

        $users = User::where('role', 'user')->get();

        $tickets = Ticket::with(['user', 'messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->orderBy('created_at', 'desc')->get();

        if ($users->isEmpty()) {
            Log::info('No users found for the dashboard.');
        }

        return view('admin.dashboard', compact('users', 'tickets'));
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = 1;
        $user->save();

        return redirect()->back()->with('message', 'User banned successfully.');
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = 0;
        $user->save();

        return redirect()->back()->with('message', 'User unbanned successfully.');
    }
}
