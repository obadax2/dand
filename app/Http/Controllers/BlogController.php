<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
            'price' => 'required|numeric|min:0',
            'visibility' => 'required|in:full,partial',
        ]);

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

        $friendIds = $user->allFriends()->pluck('id')->toArray();

        $followerIds = $user->followers()
            ->whereNotIn('users.id', $friendIds)
            ->pluck('users.id')
            ->toArray();

        $myStories = Story::where('user_id', $user->id)->get();

        $blogs = Blog::with(['user', 'story', 'upvotes', 'downvotes', 'reviews.user'])
            ->whereIn('user_id', array_merge($friendIds, $followerIds))
            ->get()
            ->sortByDesc(function ($blog) {
                return $blog->score();
            })
            ->map(function ($blog) use ($user, $friendIds) {
                $blog->canBuy = in_array($blog->user_id, $friendIds);
                return $blog;
            });
        return view('dashboard', [
            'blogs' => $blogs,
            'user' => $user,
            'myStories' => $myStories,
            'friends' => $friendIds,
            'following' => $followerIds,
        ]);
    }

    public function index()
    {
        $blogs = Blog::with(['upvotes', 'downvotes'])
            ->get()
            ->sortByDesc(function ($blog) {
                return $blog->score();
            });

        return view('blogs.index', compact('blogs'));
    }
}
