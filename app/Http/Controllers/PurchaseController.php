<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
        ]);

        $blog = Blog::findOrFail($request->blog_id);

        $buyer = Auth::user();

        if ($blog->user_id == $buyer->id) {
            return redirect()->back()->with('error', 'You cannot purchase your own blog.');
        }


        // \App\Models\Purchase::create([
        //     'user_id' => $buyer->id,
        //     'blog_id' => $blog->id,
        //     'amount' => $blog->price,
        //     'purchased_at' => now(),
        // ]);

        return redirect()->back()->with('success', 'You have purchased the blog for $' . number_format($blog->price, 2));
    }
}
