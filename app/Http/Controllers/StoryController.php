<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Character;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Add this line
use App\Models\User;

class StoryController extends Controller
{
    // Show the initial form
    public function create()
    {
        return view('stories.create');
    }

    // Handle the initial prompt submission and display generated content
    public function generate(Request $request)
    {
        // Validate the initial prompt
        $request->validate([
            'prompt' => 'required|string|max:255',
        ]);

        $prompt = $request->input('prompt');


        $aiGeneratedContent = "Once upon a time, in a land far, far away...";

        // Store the generated content in the session to carry it to the next step
        Session::put('generated_content', $aiGeneratedContent);

        // Return the view with the generated content and the form to complete the story
        return view('stories.create', [
            'generatedContent' => $aiGeneratedContent,
            'prompt' => $prompt // Pass the prompt back as well if needed
        ]);
    }

    // Handle the final story save with title and genre
    public function store(Request $request)
    {
        // Validate the final input
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            // We don't need to validate 'content' as it's coming from the session
        ]);

        // Retrieve the generated content from the session
        $content = Session::get('generated_content');

        // Check if content exists in session (should always be there if flow is correct)
        if (!$content) {
            return redirect()->route('stories.create')->with('error', 'Generated content not found. Please try again.');
        }

        $title = $request->input('title');
        $genre = $request->input('genre');

        // --- Placeholder for generating characters and map (optional) ---
        // If your AI generates characters and maps based on the content, you might
        // want to call the AI again here or process the generated content to extract them.
        // For simplicity in this example, we'll use mock data again.
        $aiResponseForCharactersAndMap = [
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
        // --- End of Placeholder ---


        // Save story
        $story = Story::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $content,
            'genre' => $genre,
            'status' => 'draft',
        ]);

        // Save characters (using the mock data for now)
        foreach ($aiResponseForCharactersAndMap['characters'] as $char) {
            Character::create([
                'story_id' => $story->id,
                'name' => $char['name'],
                'description' => $char['description'],
                'image_url' => $char['image_url'],
            ]);
        }

        // Save map (using the mock data for now)
        Map::create([
            'user_id' => Auth::id(),
            'title' => $aiResponseForCharactersAndMap['map']['title'],
            'image' => $aiResponseForCharactersAndMap['map']['image'],
            'description' => $aiResponseForCharactersAndMap['map']['description'],
        ]);

        // Clear the generated content from the session after saving
        Session::forget('generated_content');

        return redirect()->route('stories.create')->with('success', 'Story saved successfully!');
    }

    // Placeholder for AI API call
    /*
    private function callAI($prompt)
    {
        // Implement your AI API call here to get text content
        // Return the generated text content
    }
    */
    public function drafts()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your drafts.');
        }

        $user = Auth::user();

        // Fetch the user's stories with status 'draft'
        $draftStories = $user->stories()->whereIn('status', ['purchased', 'draft'])->get();
        // Pass the draft stories to a view
        return view('stories.drafts', compact('draftStories'));
    }
    public function edit(Story $story)
    {
        // Ensure the authenticated user owns this story
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); // Or redirect with an error message
        }

        // Pass the story data to the edit view
        return view('stories.edit', compact('story'));
    }

    /**
     * Update the specified story in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        // Ensure the authenticated user owns this story
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); // Or redirect with an error message
        }

        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'content' => 'required|string', // Content is now directly editable
            'status' => 'required|string|in:draft,published', // Allow changing status
        ]);

        // Update the story attributes
        $story->title = $request->input('title');
        $story->genre = $request->input('genre');
        $story->content = $request->input('content');
        $story->status = $request->input('status'); // Update status if needed

        // Save the changes
        $story->save();

        // Redirect back to the drafts page or show a success message
        return redirect()->route('stories.drafts')->with('success', 'Story updated successfully!');
    }
    public function destroy(Story $story)
    {
        // Ensure the authenticated user owns this story
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deletion if the story is 'purchased'
        if ($story->status === 'purchased') {
            return redirect()->route('stories.drafts')->with('error', 'Purchased stories cannot be deleted.');
        }

        // Delete the story
        $story->delete();

        // Redirect back with success message
        return redirect()->route('stories.drafts')->with('success', 'Story deleted successfully!');
    }
}
