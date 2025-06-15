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

    $story = $conversation->story;

    // Prepare story context
    $storyContext = $story ? $story->title . "\n\n" . $story->content : 'No story context available.';

    // Prepare characters
    $charactersText = '';
    if ($story && $story->characters->count() > 0) {
        $charactersText = "Characters:\n";
        foreach ($story->characters as $character) {
            $charactersText .= "- {$character->name}: {$character->description}\n";
        }
    } else {
        $charactersText = "Characters: No character data available.\n";
    }

    // Build initial prompt for AI
    $initialPrompt = <<<EOT
You are an AI assistant helping a user explore and discuss the following story.

Story:
{$storyContext}

{$charactersText}

Introduce yourself briefly and invite the user to ask questions or talk about the story.
EOT;

    // Send to AI
    $url = env('PYTHON_AI_URL', 'http://localhost:5000/generated');

    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'json' => [
                'prompt' => $initialPrompt,
            ],
        ]);

        $apiResponse = json_decode($response->getBody(), true);
        $botMessage = $apiResponse['generated_text'] ?? 'Hello! I’m ready to chat about your story.';
    } catch (\Exception $e) {
        $botMessage = 'Error connecting to AI service.';
    }

    // Save AI intro message
    $conversation->messages()->create([
        'sender' => 'bot',
        'message' => $botMessage,
    ]);

    return redirect()->route('chat.show', $conversation);
}


    public function show(Conversation $conversation)
    {
        $messages = $conversation->messages()->oldest()->get(); // NOT paginate()

        $story = $conversation->story;

        return view('chat.show', compact('conversation', 'story', 'messages'));
    }

public function sendMessage(Request $request, Conversation $conversation)
{
    $request->validate(['message' => 'required|string']);

    $conversation->messages()->create([
        'sender' => 'user',
        'message' => $request->message,
    ]);

    // Get limited chat history
    $history = $conversation->messages()
        ->latest()
        ->take(10)
        ->get()
        ->reverse()
        ->map(fn($msg) => ucfirst($msg->sender) . ': ' . $msg->message)
        ->implode("\n");

    // Build prompt with just history and new user message
    $fullPrompt = <<<EOT
Conversation so far:
{$history}

User: {$request->message}
Bot:
EOT;

    // Send to AI
    $url = env('PYTHON_AI_URL', 'http://localhost:5000/generated');

  try {
    $client = new \GuzzleHttp\Client();
    $response = $client->post($url, [
        'json' => [
            'prompt' => $fullPrompt,
        ],
    ]);

    $apiResponse = json_decode($response->getBody(), true);
    $botMessage = $apiResponse['generated_text'] ?? 'Sorry, I could not generate a reply at this time.';

    // Remove <think> block
    $botMessage = preg_replace('/<think>.*?<\/think>/s', '', $botMessage);
    $botMessage = trim($botMessage);
} catch (\Exception $e) {
    $botMessage = 'Error connecting to AI service.';
}

    $conversation->messages()->create([
        'sender' => 'bot',
        'message' => $botMessage,
    ]);

    if ($request->ajax()) {
        $messages = $conversation->messages()->oldest()->get();
        $view = view('chat.partials.messages', compact('messages'))->render();

        return response()->json([
            'messages_html' => $view,
        ]);
    }

    return redirect()->route('chat.show', $conversation);
}

    public function clear(Conversation $conversation)
{
    $conversation->messages()->delete();

    return redirect()->route('chat.show', $conversation)->with('status', 'Chat cleared.');
}

}
