<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Character;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class StoryController extends Controller
{
    // Remove HuggingFaceService injection since we call Python API now

     public function create()
    {
        return view('stories.create');
    }

   public function generate(Request $request)
{
    set_time_limit(120);

    $request->validate([
        'prompt' => 'required|string|max:255',
    ]);

    $userPrompt = $request->input('prompt');

    // Append system instructions to ensure structured character output
    $augmentedPrompt = $userPrompt . "\n\n"
        . "After the story, provide a list of main characters. For each character, write:\n\n"
        . "Name: [Character's name]\n"
        . "Description: [A short description of the character, including personality, role, and appearance]\n\n"
        . "Separate each character with a blank line.";

    try {
        $aiResponse = $this->callPythonAIService($augmentedPrompt);

        if (!$aiResponse) {
            dd('Failed to get a valid response from AI service.');
        }

        $aiGeneratedContent = $aiResponse['generated_text'] ?? null;

        if (empty($aiGeneratedContent)) {
            dd('AI returned empty content.');
        }

        $characters = $this->extractCharacters($aiGeneratedContent);

        // Debug characters output
      

        // Store generated story and characters in session
        Session::put('generated_content', $aiGeneratedContent);
        Session::put('generated_characters', $characters);

        return view('stories.create', [
            'generatedContent' => $aiGeneratedContent,
            'characters' => $characters,
            'prompt' => $userPrompt
        ]);
    } catch (\Exception $e) {
        dd('Exception caught in generate(): ' . $e->getMessage(), $e->getTrace());
    }
}

    private function callPythonAIService(string $prompt): ?array
    {
        $url = env('PYTHON_AI_URL', 'http://localhost:8000/generate');

        try {
           $response = Http::timeout(120)->post($url, ['prompt' => $prompt]);

            if ($response->failed()) {
                dd('Python AI Service failed:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            $json = $response->json();

            if (!$json) {
                dd('Python AI Service returned invalid JSON:', $response->body());
            }

            return $json;
        } catch (\Exception $e) {
            dd('Exception in callPythonAIService:', $e->getMessage());
        }
    }

    private function extractCharacters(string $text): array
    {
        preg_match_all('/Name:\s*(.+?)\nDescription:\s*(.+?)(?:\n|$)/s', $text, $matches, PREG_SET_ORDER);

        $characters = [];
        foreach ($matches as $match) {
            $characters[] = [
                'name' => trim($match[1]),
                'description' => trim($match[2]),
                'image_url' => null,
            ];
        }

        return $characters;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
        ]);

        $content = Session::get('generated_content');
        $characters = Session::get('generated_characters', []);

        if (!$content) {
            dd('Generated content not found in session.');
        }

        $title = $request->input('title');
        $genre = $request->input('genre');

        $story = Story::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $content,
            'genre' => $genre,
            'status' => 'draft',
        ]);

        foreach ($characters as $char) {
            Character::create([
                'story_id' => $story->id,
                'name' => $char['name'],
                'description' => $char['description'],
                'image_url' => $char['image_url'],
            ]);
        }

        Session::forget(['generated_content', 'generated_characters']);

        return redirect()->route('stories.create')->with('success', 'Story saved successfully!');
    }

    public function drafts()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your drafts.');
        }

        $user = Auth::user();
        $draftStories = $user->stories()->whereIn('status', ['purchased', 'draft'])->get();

        return view('stories.drafts', compact('draftStories'));
    }

    public function edit(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('stories.edit', compact('story'));
    }

    public function update(Request $request, Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published',
        ]);

        $story->update([
            'title' => $request->input('title'),
            'genre' => $request->input('genre'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('stories.drafts')->with('success', 'Story updated successfully!');
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($story->status === 'purchased') {
            return redirect()->route('stories.drafts')->with('error', 'Purchased stories cannot be deleted.');
        }

        $story->delete();

        return redirect()->route('stories.drafts')->with('success', 'Story deleted successfully!');
    }
}
