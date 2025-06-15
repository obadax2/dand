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
        $stories = auth()->user()->stories;
        return view('chat.select_story', compact('stories'));
    }

    public function startConversation(Request $request)
    {
        set_time_limit(60);

        $conversation = Conversation::firstOrCreate([
            'user_id' => auth()->id(),
            'story_id' => $request->story_id,
        ]);

        $story = $conversation->story;

        // Combine story and characters into a single text block
        if ($story) {
            $storyText = "{$story->title}\n\n{$story->content}\n\n";

            if ($story->characters->count() > 0) {
                $characterDescriptions = $story->characters->map(function ($character) {
                    return "{$character->name}: {$character->description}";
                })->implode("\n");

                $storyText .= "\nThe story features the following characters:\n{$characterDescriptions}";
            } else {
                $storyText .= "\nNo character information is available for this story.";
            }
        } else {
            $storyText = "No story or character data available.";
        }

        // Final prompt sent to the AI
        $initialPrompt = <<<EOT
You are an AI assistant helping a user explore and discuss a story.

{$storyText}

Please introduce yourself briefly and invite the user to ask questions or discuss aspects of the story.
EOT;

        // Send to AI
        $url = env('PYTHON_AI_GENERATED_URL', 'http://localhost:5000/generated');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($url, [
                'json' => [
                    'prompt' => $initialPrompt,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $apiResponse = json_decode($responseBody, true);

            $botMessage = $apiResponse['response'] ?? 'Hello! I’m ready to chat about your story.';
        } catch (\Exception $e) {
            dd('Error calling API:', $e->getMessage());
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
        set_time_limit(120);
        $request->validate(['message' => 'required|string']);

        // Save user message
        $conversation->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);

        // Get limited chat history (last 10 messages)
        $history = $conversation->messages()
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn($msg) => ucfirst($msg->sender) . ': ' . $msg->message)
            ->implode("\n");

        // Build prompt with history and new message
        $fullPrompt = <<<EOT
Conversation so far:
{$history}

User: {$request->message}
Bot:
EOT;

        // Send to AI
        $url = env('PYTHON_AI_GENERATED_URL', 'http://localhost:5000/generated');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($url, [
                'json' => [
                    'prompt' => $fullPrompt,
                ],
                // optional headers if needed
                // 'headers' => [
                //     'Authorization' => 'Bearer YOUR_API_KEY',
                // ],
            ]);

            // Check response status
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                // Dump status and body for debugging
                dd('API responded with status: ' . $statusCode, 'Body:', $response->getBody()->getContents());
            }

            $responseBody = $response->getBody()->getContents();

            // Decode JSON response
            $apiResponse = json_decode($responseBody, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                dd('JSON decode error:', json_last_error_msg(), 'Response:', $responseBody);
            }

            // Use AI response or fallback message
            $botMessage = $apiResponse['response'] ?? 'Sorry, I could not generate a reply at this time.';
        } catch (\Exception $e) {
            dd('Failed to send message', $e->getMessage(), $e->getTraceAsString());
            $botMessage = 'Error connecting to AI service.';
        }

        // Save bot message
        $conversation->messages()->create([
            'sender' => 'bot',
            'message' => $botMessage,
        ]);

        // Return AJAX response with updated messages or redirect to conversation view
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
