<?php

namespace App\Http\Controllers;

use App\Models\SlotFill;
use App\Models\SlotJob;
use App\Services\SlotExtractionService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class SlotJobController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', $this->pageProps());
    }

    public function store(Request $request, SlotExtractionService $extractor): Response
    {
        $validated = $request->validate([
            'source_text' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        $job = SlotJob::create([
            'source_text' => $validated['source_text'],
            'status' => 'processing',
        ]);

        try {
            $slots = $extractor->extract($job->source_text);
        } catch (Throwable) {
            $slots = [
                'date' => null,
                'window' => null,
                'city' => null,
                'missing' => ['date', 'window', 'city'],
            ];
        }

        $job->update(['status' => $slots['missing'] === [] ? 'filled' : 'needs_review']);

        $fill = $job->slotFill()->create([
            'date' => $slots['date'],
            'window' => $slots['window'],
            'city' => $slots['city'],
            'missing' => $slots['missing'],
        ]);

        return Inertia::render('Welcome', $this->pageProps($fill));
    }

    /**
     * @return array{recentJobs: array<int, array<string, mixed>>, result: array<string, mixed>|null}
     */
    private function pageProps(?SlotFill $result = null): array
    {
        $recentJobs = SlotJob::query()
            ->with('slotFill')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (SlotJob $job): array => [
                'id' => $job->id,
                'source_text' => $job->source_text,
                'status' => $job->status,
                'date' => $this->formatDate($job->slotFill),
                'window' => $job->slotFill?->window,
                'city' => $job->slotFill?->city,
                'missing' => $job->slotFill->missing ?? ['date', 'window', 'city'],
            ])
            ->all();

        return [
            'recentJobs' => $recentJobs,
            'result' => $result === null ? null : [
                'id' => $result->slot_job_id,
                'source_text' => $result->job->source_text,
                'date' => $this->formatDate($result),
                'window' => $result->window,
                'city' => $result->city,
                'missing' => $result->missing,
            ],
        ];
    }

    private function formatDate(?SlotFill $fill): ?string
    {
        $date = $fill?->getRawOriginal('date');

        return is_string($date) ? CarbonImmutable::parse($date)->toDateString() : null;
    }
}
