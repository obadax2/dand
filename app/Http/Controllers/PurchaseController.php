<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        // Validate input
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
        ]);

        // Retrieve the blog
        $blog = Blog::findOrFail($request->blog_id);

        $buyer = Auth::user();

        // Prevent buying your own blog
        if ($blog->user_id == $buyer->id) {
            return redirect()->back()->with('error', 'You cannot purchase your own blog.');
        }

        // Optional: Check if already purchased (if you have a purchases table)
        // For now, assume no duplicate purchase tracking

        // Process payment logic here
        // For simplicity, we'll just simulate a purchase
        // In real app, integrate payment gateway here

        // Record the purchase (assuming a purchases table exists)
        // For demonstration, let's assume you have a Purchase model
        // \App\Models\Purchase::create([
        //     'user_id' => $buyer->id,
        //     'blog_id' => $blog->id,
        //     'amount' => $blog->price,
        //     'purchased_at' => now(),
        // ]);

        // For now, just show success message
        return redirect()->back()->with('success', 'You have purchased the blog for $' . number_format($blog->price, 2));
    }
}   