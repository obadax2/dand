<?php

namespace App\Services;

use Kambo\Huggingface\Huggingface;
use Kambo\Huggingface\Enums\Type;
use Kambo\Huggingface\ValueObjects\Transporter\Payload;
use Kambo\Huggingface\Transporters\HttpTransporter;
use Kambo\Huggingface\ValueObjects\Transporter\BaseUri;
use Kambo\Huggingface\ValueObjects\Transporter\Headers;
use Kambo\Huggingface\ValueObjects\Transporter\QueryParams;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Illuminate\Support\Facades\Http;
class HuggingFaceService
{
    protected $client;

    public function __construct()
    {
        // Replace env(...) with your actual key string if needed for quick testing
        $apiKey = env('HF_API_KEY');
        // Debug: Check if API key is loaded correctly
        if (!$apiKey) {
            dd('HF_API_KEY is not set or empty');
        }

        $this->client = Huggingface::client($apiKey);
    }

public function generateStoryAndCharacters(string $prompt): array
{
    $model = 'Qwen/Qwen3-30B-A3B';
    $url = "https://api-inference.huggingface.co/models/{$model}";

    $messages = [
        ['role' => 'user', 'content' => $prompt]
    ];

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer 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',
            'Content-Type' => 'application/json',
        ])
        ->withoutVerifying()  // Disable SSL check if you want locally only
        ->post($url, [
            'inputs' => [
                'messages' => $messages,
            ],
            'parameters' => [
                'max_new_tokens' => 500,
                'wait_for_model' => true,
            ],
        ]);

        if ($response->failed()) {
            dd('Huggingface API request failed:', $response->status(), $response->body());
        }

        $data = $response->json();

        // Inspect the response structure - it might be something like:
        // ["generated_text" => "..."] or ["choices" => [["message" => ["content" => "..."]]]]

        // Example parsing (adjust based on actual response shape):
        $content = null;

        if (isset($data['generated_text'])) {
            $content = $data['generated_text'];
        } elseif (isset($data['choices'][0]['message']['content'])) {
            $content = $data['choices'][0]['message']['content'];
        } else {
            dd('Unknown response format', $data);
        }

        return [
            'raw' => $content,
            'characters' => $this->extractCharacters($content),
        ];
    } catch (\Exception $e) {
        dd('Exception caught:', $e->getMessage());
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

        // Debug: show extracted characters array
        // dd('Extracted characters:', $characters);

        return $characters;
    }
}
