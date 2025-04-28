<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Create a new poll
        Poll::create([
            'title' => $request->title,
            'yes_count' => 0,
            'no_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Poll created successfully!');
    }
}