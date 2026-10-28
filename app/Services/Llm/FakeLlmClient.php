<?php

namespace App\Services\Llm;

class FakeLlmClient implements LlmClient
{
    /** @var array<int, array{sourceText: string, missingFields: array<int, string>}> */
    public array $calls = [];

    /**
     * @param  array<string, mixed>  $response
     */
    public function __construct(private readonly array $response = []) {}

    public function extractMissing(string $sourceText, array $missingFields): array
    {
        $this->calls[] = [
            'sourceText' => $sourceText,
            'missingFields' => $missingFields,
        ];

        return $this->response;
    }
}
