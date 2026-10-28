<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use Throwable;

class GeminiLlmClient implements LlmClient
{
    public function __construct(private readonly string $apiKey) {}

    public function extractMissing(string $sourceText, array $missingFields): array
    {
        if ($missingFields === []) {
            return [];
        }

        try {
            $response = Http::connectTimeout(4)
                ->timeout(12)
                ->acceptJson()
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.$this->apiKey, [
                    'contents' => [[
                        'parts' => [[
                            'text' => 'Extract only these missing booking fields: '.implode(', ', $missingFields).'. Return one JSON object with only those keys. Use date as YYYY-MM-DD, window as morning, afternoon, or evening, and city as a city name. If a value is not explicit, omit it. Do not infer or invent information. Request: '.$sourceText,
                        ]],
                    ]],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0,
                    ],
                ]);

            if (! $response->successful()) {
                return [];
            }

            $text = $response->json('candidates.0.content.parts.0.text');
            if (! is_string($text)) {
                return [];
            }

            $result = json_decode($text, true);

            return is_array($result) ? $result : [];
        } catch (Throwable) {
            return [];
        }
    }
}
