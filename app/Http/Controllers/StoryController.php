<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Character;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    // Show the form
    public function create()
    {
        return view('stories.create');
    }

    // Handle form submission
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'prompt' => 'required|string|max:255',
        ]);

        $prompt = $request->input('prompt');

        // Placeholder for AI call - replace with actual API integration later
        // Example: $aiResponse = $this->callAI($prompt);
        // For now, mock data:
        $aiResponse = [
            'story_title' => 'Generated Story Title',
            'characters' => [
                ['name' => 'Hero', 'description' => 'Brave hero of the story', 'image_url' => null],
                ['name' => 'Villain', 'description' => 'Evil villain', 'image_url' => null],
            ],
            'map' => [
                'title' => 'Mystic Forest',
                'image' => 'path/to/map/image.png',
                'description' => 'A mysterious forest with hidden secrets.',
            ],
        ];

        // Save story
        $story = Story::create([
            'user_id' => Auth::id(),
            'title' => $aiResponse['story_title'],
            'content' => $prompt, // or AI-generated content
            'genre' => 'Fantasy', // or derive from AI response
            'status' => 'draft',
        ]);

        // Save characters
        foreach ($aiResponse['characters'] as $char) {
            Character::create([
                'story_id' => $story->id,
                'name' => $char['name'],
                'description' => $char['description'],
                'image_url' => $char['image_url'],
            ]);
        }

        // Save map
        Map::create([
            'user_id' => Auth::id(),
            'title' => $aiResponse['map']['title'],
            'image' => $aiResponse['map']['image'],
            'description' => $aiResponse['map']['description'],
        ]);

        return redirect()->route('stories.create')->with('success', 'Story created successfully!');
    }

    // Placeholder for AI API call
    /*
    private function callAI($prompt)
    {
        // Implement your AI API call here
        // Return parsed response
    }
    */
}