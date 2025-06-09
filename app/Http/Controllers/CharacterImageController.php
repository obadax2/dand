<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CharacterImageController extends Controller
{
    public function generateImages(Request $request)
    {
        set_time_limit(300);

        $storyId = $request->input('story_id');
        $story = Story::with('characters')->findOrFail($storyId);

        foreach ($story->characters as $character) {
            $description = $character->description;
            $characterId = (string) $character->id;

            // Call FastAPI endpoint
           $response = Http::timeout(120)->post('http://127.0.0.1:8002/generate-image', [
    'description' => $description,
    'character_id' => $characterId,
]);


            if (!$response->ok()) {
                return back()->with('error', "Failed to generate image for {$character->name}: " . $response->body());
            }

            $data = $response->json();

            if (!isset($data['base64_image'])) {
                return back()->with('error', "No image returned for {$character->name}");
            }

            // Decode base64 and save the image to public storage
            $imageData = base64_decode($data['base64_image']);
            $filename = "character_images/{$characterId}.png";

            Storage::disk('public')->put($filename, $imageData);

            // Save the image path/URL to character record
            // Assuming your Character model has a 'image_url' or 'image_path' column
            $character->image_url = Storage::url($filename);  // This generates a public URL
            $character->save();
        }

        return back()->with('success', 'Character images generated and saved successfully!');
    }
}
