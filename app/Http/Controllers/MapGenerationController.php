<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
class MapGenerationController extends Controller
{
    public function selectStory()
    {
        $stories = Story::where('user_id', auth()->id())->get();
        return view('maps.select', compact('stories'));
    }

   public function generateMap(Request $request)
{
    set_time_limit(5000);
    $request->validate([
        'story_id' => 'required|exists:stories,id'
    ]);

    $story = Story::findOrFail($request->story_id);
$response = Http::asForm()
    ->timeout(50000) // Guzzle timeout
    ->withOptions(['verify' => false]) // Skip SSL (for dev)
    ->post('https://41c8-35-201-144-240.ngrok-free.app/generate-map', [
        'prompt' => $story->content,
        'story_id' => $story->id,
    ]);

    if ($response->failed()) {
        return back()->with('error', 'Map generation failed!');
    }

    $data = $response->json();
    $imagePath = $data['path'];

    // Save map record
    $map = new \App\Models\Map();
    $map->story_id = $story->id;
    $map->image_path = $imagePath;
    $map->save();

    return back()->with('success', 'Map generated and saved!');
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
        'map_image' => 'required|image|max:5120', // max 5MB
    ]);

    $story = Story::findOrFail($request->story_id);

    // Store the image file
    $path = $request->file('map_image')->store('maps', 'public');

    // Save image path in your map table
    $map = new \App\Models\Map();
    $map->story_id = $story->id;
    $map->image_path = $path;
    $map->save();

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
    $map->image_path = $path;
    $map->save();

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
    $map->image_path = $path;
    $map->save();

    return response()->json(['message' => 'Map uploaded successfully', 'path' => $path]);
}
}
