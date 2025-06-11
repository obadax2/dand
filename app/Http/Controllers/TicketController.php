<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\User;

use function Laravel\Prompts\alert;

class TicketController extends Controller
{
    // User creates ticket + initial message
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Create the ticket (no content column needed now)
        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'username' => auth()->user()->name,
        ]);
        // Create initial message as user message
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->content,
            'sender' => 'user',
        ]);

        return back()->with('success', 'Ticket submitted successfully.');
    }

    // Admin view all tickets
    public function index()
    {
        $tickets = Ticket::with('user', 'messages')->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    // Admin replies to ticket
    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $ticket->messages()->create([
<<<<<<< HEAD
            'user_id' => $ticket->user_id, // associate with ticket owner or admin user ID if available
=======
            'user_id' => null, // or the admin's ID if needed
>>>>>>> 29c5223eef861cdd8275f6a34812cd1da512d169
            'message' => $request->input('reply'),
            'sender' => 'admin',
        ]);

<<<<<<< HEAD
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reply sent successfully.']);
        }

        return redirect()->route('tickets.index')->with('success', 'Reply sent successfully.');
    }


=======
        // Return JSON if it's an AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Reply sent successfully.']);
        }

        // Fallback for normal requests
        return redirect()->route('tickets.index')->with('success', 'Reply sent successfully.');
    }

>>>>>>> 29c5223eef861cdd8275f6a34812cd1da512d169
    // Show ticket with conversation for user
    public function show(Ticket $ticket)
    {
        // Authorization: only owner can see
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('messages.user'); // eager load messages and users

        return view('tickets.show', compact('ticket'));
    }

    // User replies to admin message on ticket
    public function userReply(Request $request, Ticket $ticket)
    {
        // Authorization: only owner can reply
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'sender' => 'user',
        ]);

        return back()->with('success', 'Your reply was sent.');
    }
    public function notifications()
    {
        $userId = auth()->id();

        // Get latest admin replies to user's tickets
        $messages = \App\Models\Message::whereHas('ticket', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('sender', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return response()->json($messages);
    }
    public function messages(Ticket $ticket)
    {
        $messages = $ticket->messages()->latest()->get()->map(function ($msg) {
            return [
                'sender' => $msg->sender,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('d M Y, H:i'),
            ];
        });

        return response()->json(['messages' => $messages]);
    }

    public function ajaxReply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }
}
