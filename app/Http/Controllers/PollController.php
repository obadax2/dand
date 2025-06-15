<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PollVote;

class PollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Poll::create([
            'title' => $request->title,
            'yes_count' => 0,
            'no_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Poll created successfully!');
    }
    public function vote(Request $request, Poll $poll, $vote)
    {
        $user = Auth::user();

        if (!in_array($vote, ['yes', 'no'])) {
            return redirect()->back()->with('error', 'Invalid vote option.');
        }

        $existingVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingVote) {
            return redirect()->back()->with('error', 'You have already voted on this poll.');
        }

        PollVote::create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'vote' => $vote,
        ]);

        if ($vote === 'yes') {
            $poll->increment('yes_count');
        } else {
            $poll->increment('no_count');
        }

        return redirect()->back()->with('success', 'Thank you for voting!');
    }
    public function destroy(Poll $poll)
    {
        $poll->delete();
        return redirect()->back()->with('success', 'Poll deleted successfully.');
    }
}
