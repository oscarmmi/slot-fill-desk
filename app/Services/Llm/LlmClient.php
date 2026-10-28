<?php

namespace App\Services\Llm;

interface LlmClient
{
    /**
     * Extract only the requested fields from a booking request.
     *
     * @param  array<int, string>  $missingFields
     * @return array<string, mixed>
     */
    public function extractMissing(string $sourceText, array $missingFields): array;
}
