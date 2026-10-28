<?php

namespace Database\Seeders;

use App\Models\SlotJob;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $requests = [
            [
                'source_text' => 'Pickup tomorrow afternoon in Miami',
                'date' => now()->addDay()->toDateString(),
                'window' => 'afternoon',
                'city' => 'Miami',
            ],
            [
                'source_text' => 'Please arrange a morning pickup on 2026-11-03 in Austin.',
                'date' => '2026-11-03',
                'window' => 'morning',
                'city' => 'Austin',
            ],
            [
                'source_text' => 'Need a delivery this evening in Chicago.',
                'date' => null,
                'window' => 'evening',
                'city' => 'Chicago',
            ],
        ];

        foreach ($requests as $request) {
            $job = SlotJob::create([
                'source_text' => $request['source_text'],
                'status' => $request['date'] === null ? 'needs_review' : 'filled',
            ]);

            $job->slotFill()->create([
                'date' => $request['date'],
                'window' => $request['window'],
                'city' => $request['city'],
                'missing' => $request['date'] === null ? ['date'] : [],
            ]);
        }
    }
}
