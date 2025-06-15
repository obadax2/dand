<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\User;

use function Laravel\Prompts\alert;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'username' => auth()->user()->name,
        ]);
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->content,
            'sender' => 'user',
        ]);

        return back()->with('success', 'Ticket submitted successfully.');
    }
    public function index()
    {
        $tickets = Ticket::with('user', 'messages')->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $ticket->messages()->create([
            'user_id' => $ticket->user_id,
            'message' => $request->input('reply'),
            'sender' => 'admin',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reply sent successfully.']);
        }

        return redirect()->route('tickets.index')->with('success', 'Reply sent successfully.');
        $ticket->messages()->create([
            'user_id' => null,
            'message' => $request->input('reply'),
            'sender' => 'admin',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reply sent successfully.']);
        }

        return redirect()->route('tickets.index')->with('success', 'Reply sent successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('messages.user');

        return view('tickets.show', compact('ticket'));
    }

    public function userReply(Request $request, Ticket $ticket)
    {
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

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('home')->with('success', 'Ticket deleted successfully.');
    }
}
