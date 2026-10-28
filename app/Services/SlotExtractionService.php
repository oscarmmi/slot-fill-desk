<?php

namespace App\Services;

use App\Services\Llm\LlmClient;
use Carbon\CarbonImmutable;
use Throwable;

class SlotExtractionService
{
    private const CITIES = [
        'Miami',
        'New York',
        'Los Angeles',
        'Chicago',
        'Austin',
        'Seattle',
        'Boston',
        'Denver',
        'San Francisco',
    ];

    private const WINDOWS = ['morning', 'afternoon', 'evening'];

    public function __construct(private readonly LlmClient $llm) {}

    /**
     * @return array{date: string|null, window: string|null, city: string|null, missing: array<int, string>}
     */
    public function extract(string $sourceText): array
    {
        $slots = [
            'date' => $this->extractDate($sourceText),
            'window' => $this->extractWindow($sourceText),
            'city' => $this->extractCity($sourceText),
        ];

        $missing = array_keys(array_filter($slots, fn (mixed $value): bool => $value === null));

        if ($missing !== []) {
            try {
                $suggestions = $this->llm->extractMissing($sourceText, $missing);

                foreach ($missing as $field) {
                    $value = $suggestions[$field] ?? null;
                    if ($this->isValidSuggestion($field, $value)) {
                        $slots[$field] = $field === 'date'
                            ? CarbonImmutable::parse($value)->toDateString()
                            : trim($value);
                    }
                }
            } catch (Throwable) {
                // Retrieval-only behavior is the fallback when the optional model is unavailable.
            }
        }

        return [
            ...$slots,
            'missing' => array_keys(array_filter($slots, fn (mixed $value): bool => $value === null)),
        ];
    }

    private function extractDate(string $sourceText): ?string
    {
        if (preg_match('/\b(20\d{2}-\d{2}-\d{2})\b/', $sourceText, $matches) === 1) {
            try {
                $date = CarbonImmutable::createFromFormat('!Y-m-d', $matches[1]);

                if ($date !== null && $date->format('Y-m-d') === $matches[1]) {
                    return $date->toDateString();
                }
            } catch (Throwable) {
                return null;
            }
        }

        if (preg_match('/\btomorrow\b/i', $sourceText) === 1) {
            return CarbonImmutable::tomorrow()->toDateString();
        }

        if (preg_match('/\btoday\b/i', $sourceText) === 1) {
            return CarbonImmutable::today()->toDateString();
        }

        return null;
    }

    private function extractWindow(string $sourceText): ?string
    {
        foreach (self::WINDOWS as $window) {
            if (preg_match('/\b'.preg_quote($window, '/').'\b/i', $sourceText) === 1) {
                return $window;
            }
        }

        if (preg_match('/\btonight\b/i', $sourceText) === 1) {
            return 'evening';
        }

        return null;
    }

    private function extractCity(string $sourceText): ?string
    {
        foreach (self::CITIES as $city) {
            if (preg_match('/\b'.preg_quote($city, '/').'\b/i', $sourceText) === 1) {
                return $city;
            }
        }

        return null;
    }

    private function isValidSuggestion(string $field, mixed $value): bool
    {
        if (! is_string($value) || trim($value) === '') {
            return false;
        }

        if ($field === 'date') {
            if (preg_match('/^20\d{2}-\d{2}-\d{2}$/', $value) !== 1) {
                return false;
            }

            try {
                $date = CarbonImmutable::createFromFormat('!Y-m-d', $value);

                return $date !== null && $date->format('Y-m-d') === $value;
            } catch (Throwable) {
                return false;
            }
        }

        if ($field === 'window') {
            return in_array(strtolower(trim($value)), self::WINDOWS, true);
        }

        return $field === 'city' && mb_strlen(trim($value)) <= 100;
    }
}
