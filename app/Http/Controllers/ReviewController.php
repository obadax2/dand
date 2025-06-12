<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Blog;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
public function store(Request $request, $blogId)
{
    $blog = Blog::findOrFail($blogId);
    $userId = auth()->id();

    // Prevent user from reviewing their own blog
    if ($blog->user_id === $userId) {
        return redirect()->back()->withErrors('You cannot review your own story.');
    }

    // Check if user already reviewed this blog
    $existingReview = $blog->reviews()->where('user_id', $userId)->first();
    if ($existingReview) {
        return redirect()->back()->withErrors('You have already reviewed this story.');
    }

    // Validate input
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // Create review
    $blog->reviews()->create([
        'user_id' => $userId,
        'rating' => $validated['rating'],
        'comment' => $validated['comment'] ?? '',
    ]);

    return redirect()->back()->with('success', 'Review submitted successfully.');
}
}
