<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{
   public function index()
   {
       $stories = auth()->user()->stories; // or all if admin
       return view('chat.select_story', compact('stories'));
   }

   public function startConversation(Request $request)
   {
       $conversation = Conversation::firstOrCreate([
           'user_id' => auth()->id(),
           'story_id' => $request->story_id,
       ]);

       return redirect()->route('chat.show', $conversation);
   }

   public function show(Conversation $conversation)
   {
       $messages = $conversation->messages()->latest()->paginate(10);

       if (request()->ajax()) {
           return view('chat.partials.messages', compact('messages'));
       }

       $story = $conversation->story; // your story data to pass
       return view('chat.show', compact('conversation', 'story', 'messages'));
   }

   public function sendMessage(Request $request, Conversation $conversation)
   {
       $request->validate(['message' => 'required|string']);

       // Save user message
       $conversation->messages()->create([
           'sender' => 'user',
           'message' => $request->message
       ]);

       // --- AI INTEGRATION PLACEHOLDER ---
       /*
       // Prepare data for AI core
       $story = $conversation->story;
       $characters = $story->characters; // Collection of characters
       $map = $story->map;

       // Example: Call your AI core service (replace with actual implementation)
       $aiReply = AIChatService::getReply([
           'story' => $story,
           'characters' => $characters,
           'map' => $map,
           'user_message' => $request->message,
           'conversation_id' => $conversation->id,
       ]);

       // Save AI reply to messages
       $conversation->messages()->create([
           'sender' => 'bot',
           'message' => $aiReply,
       ]);
       */

       // Placeholder bot reply (temporary until AI core is ready)
       $conversation->messages()->create([
           'sender' => 'bot',
           'message' => 'This is a placeholder reply.'
       ]);

       if ($request->ajax()) {
           $messages = $conversation->messages()->latest()->paginate(10);
           $view = view('chat.partials.messages', compact('messages'))->render();

           return response()->json([
               'messages_html' => $view,
           ]);
       }

       return redirect()->route('chat.show', $conversation);
   }
}
