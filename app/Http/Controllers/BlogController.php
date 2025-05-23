<?php

// app/Http/Controllers/BlogController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function create(Request $request)
    {
        // Validate input
        $request->validate([
            'story_id' => 'required|exists:stories,id',
            'price' => 'required|numeric|min:0',
            'visibility' => 'required|in:full,partial',
        ]);

        // Create the blog
        $blog = Blog::create([
            'user_id' => Auth::id(),
            'story_id' => $request->story_id,
            'price' => $request->price,
            'visibility' => $request->visibility,
        ]);

        return redirect()->back()->with('success', 'Blog created successfully.');
    }
  public function dashboard()
{
    $user = Auth::user();

    // Get all accepted friends
    $friendIds = $user->allFriends()->pluck('id')->toArray();

    // Fetch stories created by user and friends
    $myStories = Story::where('user_id', $user->id)->get();
        

    // Fetch blogs for user and friends
    $ids = array_merge([$user->id], $friendIds);
    $blogs = Blog::with('user')
        ->whereIn('user_id', $ids)
        ->get();

    return view('dashboard', compact( 'blogs', 'user','myStories'));
}
}
