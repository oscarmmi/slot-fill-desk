<?php

use App\Models\SlotFill;
use App\Models\SlotJob;
use App\Services\Llm\FakeLlmClient;
use App\Services\Llm\LlmClient;
use Carbon\CarbonImmutable;
use Inertia\Testing\AssertableInertia as Assert;

it('fills a guest request with deterministic slots and renders the result', function () {
    $fake = new FakeLlmClient;
    app()->instance(LlmClient::class, $fake);

    $response = $this->post(route('slot-jobs.store'), [
        'source_text' => 'Pickup tomorrow afternoon in Miami',
    ]);

    $response->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->where('result.window', 'afternoon')
        ->where('result.city', 'Miami')
        ->where('result.date', CarbonImmutable::tomorrow()->toDateString())
        ->where('result.missing', [])
    );

    expect($fake->calls)->toBeEmpty()
        ->and(SlotJob::query()->first()->status)->toBe('filled')
        ->and(SlotFill::query()->first()->missing)->toBe([]);
});

it('asks the fake LLM only for unresolved fields and keeps deterministic values', function () {
    $fake = new FakeLlmClient([
        'date' => '2026-11-04',
        'window' => 'afternoon',
        'city' => 'Boston',
    ]);
    app()->instance(LlmClient::class, $fake);

    $this->post(route('slot-jobs.store'), [
        'source_text' => 'Pickup in Miami',
    ])->assertOk();

    $fill = SlotFill::query()->firstOrFail();

    expect($fake->calls)->toHaveCount(1)
        ->and($fake->calls[0]['missingFields'])->toBe(['date', 'window'])
        ->and($fill->city)->toBe('Miami')
        ->and($fill->window)->toBe('afternoon')
        ->and($fill->date?->format('Y-m-d'))->toBe('2026-11-04')
        ->and($fill->missing)->toBe([]);
});

it('stays available when the optional LLM throws', function () {
    app()->instance(LlmClient::class, new class implements LlmClient
    {
        public function extractMissing(string $sourceText, array $missingFields): array
        {
            throw new RuntimeException('Model is unavailable');
        }
    });

    $this->post(route('slot-jobs.store'), [
        'source_text' => 'Could you arrange a pickup?',
    ])->assertOk();

    expect(SlotJob::query()->firstOrFail()->status)->toBe('needs_review')
        ->and(SlotFill::query()->firstOrFail()->missing)->toBe(['date', 'window', 'city']);
});

it('validates empty requests for guests', function () {
    $this->post(route('slot-jobs.store'), ['source_text' => ''])
        ->assertSessionHasErrors('source_text');

    expect(SlotJob::query()->count())->toBe(0);
});

it('seeds three fictional booking requests for the demo workspace', function () {
    $this->seed();

    expect(SlotJob::query()->count())->toBe(3)
        ->and(SlotFill::query()->count())->toBe(3)
        ->and(SlotJob::query()->where('source_text', 'like', '%Miami%')->exists())->toBeTrue();
});
