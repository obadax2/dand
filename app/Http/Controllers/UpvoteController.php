<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class UpvoteController extends Controller
{
public function store(Request $request, Blog $blog)
{
    $user = auth()->user();

    // If user already upvoted, return error
    if ($blog->upvotes()->where('user_id', $user->id)->exists()) {
        return back()->with('error', 'You already upvoted.');
    }

    // Remove any existing downvote
    $blog->downvotes()->where('user_id', $user->id)->delete();

    // Add upvote
    $blog->upvotes()->create([
        'user_id' => $user->id
    ]);

    return back()->with('success', 'Upvoted!');
}

}
