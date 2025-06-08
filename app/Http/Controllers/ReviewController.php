<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Blog;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
    public function store(Request $request, Blog $blog)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $blog->reviews()->create([
        'user_id' => auth()->id(),
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return redirect()->back()->with('success', 'Review added successfully.');
}
}
