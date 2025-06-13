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

        // Validate comment manually
        $request->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        // Manually extract rating from request
        $rating = null;
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'rating_')) {
                $rating = (int)$value;
                break;
            }
        }

        // Validate rating manually
        if (!$rating || $rating < 1 || $rating > 5) {
            return redirect()->back()->withErrors(['rating' => 'Please select a valid rating between 1 and 5.']);
        }

        // Create review
        $blog->reviews()->create([
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $request->comment ?? '',
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }
}
