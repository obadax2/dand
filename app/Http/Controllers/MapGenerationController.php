<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MapGenerationController extends Controller
{
    public function selectStory()
    {
        $stories = Story::where('user_id', auth()->id())->get();
        return view('maps.select', compact('stories'));
    }

    public function generateMap(Request $request)
    {
        set_time_limit(360);

        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        $story = Story::findOrFail($request->story_id);

        $prompt = $story->places;

        if (!$prompt) {
            return back()->with('error', 'No map description available for this story.');
        }

        $client = new \GuzzleHttp\Client([
            'timeout' => 360,
            'verify' => false,
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        try {
            $response = $client->post('https://ed50-188-227-170-2.ngrok-free.app/generate-map', [
                'json' => [
                    'prompt' => $prompt,
                    'story_id' => (string) $story->id,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                \Log::error("Map generation failed with status: " . $response->getStatusCode());
                return back()->with('error', 'Map generation failed.');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (empty($data['base64_image'])) {
                \Log::error("Map generation response missing base64 image: " . json_encode($data));
                return back()->with('error', 'Invalid response from map generation API.');
            }

            $base64Image = $data['base64_image'];

            if (strpos($base64Image, ',') !== false) {
                $base64Image = explode(',', $base64Image)[1];
            }

            $imageContents = base64_decode($base64Image);

            if ($imageContents === false) {
                \Log::error("Failed to decode base64 image from map API.");
                return back()->with('error', 'Failed to decode generated image.');
            }

            $filename = 'maps/' . \Illuminate\Support\Str::uuid() . '.png';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $imageContents);

            \Log::info('Saved map image to ' . $filename);

            $map = new \App\Models\Map();
            $map->story_id = $story->id;
            $map->user_id = auth()->id();
            $map->image = $filename;
            $map->title = 'Generated Map for: ' . \Illuminate\Support\Str::limit($story->title, 50);
            $map->description = 'Auto-generated map image for this story.';

            $map->save();

            \Log::info('Map saved successfully with ID: ' . $map->id);
        } catch (\Exception $e) {
            \Log::error('Error during map generation: ' . $e->getMessage());
            dd('Exception caught:', $e->getMessage());
            return back()->with('error', 'Map generation error: ' . $e->getMessage());
        }

        return back()->with('success', '✅ Map generated and saved!');
    }
    public function uploadForm()
    {
        $stories = Story::where('user_id', auth()->id())->get();
        return view('maps.upload', compact('stories'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
            'map_image' => 'required|image|max:20000',
        ]);

        $story = Story::findOrFail($request->story_id);

        $path = $request->file('map_image')->store('maps', 'public');

        $map = new \App\Models\Map();
        $map->story_id = $story->id;
        $map->user_id = auth()->id();
        $map->image = $path;
        $map->title = 'Uploaded Map for: ' . Str::limit($story->title, 50);
        $map->description = 'User uploaded map image for this story.';

        try {
            $map->save();
            Log::info('Uploaded map saved with ID: ' . $map->id);
        } catch (\Exception $e) {
            Log::error('Failed to save uploaded map: ' . $e->getMessage());
            return back()->with('error', 'Failed to save uploaded map: ' . $e->getMessage());
        }

        return redirect()->route('maps.upload.form')->with('success', 'Map image uploaded successfully!');
    }

    public function apiUploadImage(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
            'map_image' => 'required|image|max:5120',
        ]);

        $story = Story::findOrFail($request->story_id);

        $path = $request->file('map_image')->store('maps', 'public');

        $map = new \App\Models\Map();
        $map->story_id = $story->id;
        $map->user_id = auth()->id();
        $map->image = $path;
        $map->title = 'Uploaded Map for: ' . Str::limit($story->title, 50);
        $map->description = 'User uploaded map image for this story.';

        try {
            $map->save();
            Log::info('API uploaded map saved with ID: ' . $map->id);
        } catch (\Exception $e) {
            Log::error('Failed to save API uploaded map: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save uploaded map: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Map image uploaded successfully', 'path' => $path], 200);
    }

    public function uploadMap(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
            'map_image' => 'required|image|max:5120',
        ]);

        $story = Story::findOrFail($request->story_id);

        $path = $request->file('map_image')->store('maps', 'public');

        $map = new \App\Models\Map();
        $map->story_id = $story->id;
        $map->user_id = auth()->id();
        $map->image = $path;
        $map->title = 'Uploaded Map for: ' . Str::limit($story->title, 50);
        $map->description = 'User uploaded map image for this story.';

        try {
            $map->save();
            Log::info('UploadMap saved with ID: ' . $map->id);
        } catch (\Exception $e) {
            Log::error('Failed to save uploadMap: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save uploaded map: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Map uploaded successfully', 'path' => $path]);
    }

    public function show(Request $request) {
        $stories = Story::with('map')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('maps.my', compact('stories'));

    }
}
