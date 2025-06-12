<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class DownvoteController extends Controller
{
 public function store(Request $request, Blog $blog)
{
    $user = auth()->user();

    // If user already downvoted, return error
    if ($blog->downvotes()->where('user_id', $user->id)->exists()) {
        return back()->with('error', 'You already downvoted.');
    }

    // Remove any existing upvote
    $blog->upvotes()->where('user_id', $user->id)->delete();

    // Add downvote
    $blog->downvotes()->create([
        'user_id' => $user->id
    ]);

    return back()->with('success', 'Downvoted!');
}
}
