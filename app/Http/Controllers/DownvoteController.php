<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class DownvoteController extends Controller
{
 public function store(Request $request, Blog $blog)
{
    $user = auth()->user();

    if ($blog->downvotes()->where('user_id', $user->id)->exists()) {
        return back()->with('error', 'You already downvoted.');
    }

    $blog->upvotes()->where('user_id', $user->id)->delete();

    $blog->downvotes()->create([
        'user_id' => $user->id
    ]);

    return back()->with('success', 'Downvoted!');
}
}
