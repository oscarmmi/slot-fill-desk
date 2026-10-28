<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|null $date
 * @property-read SlotJob $job
 */
class SlotFill extends Model
{
    protected $fillable = ['slot_job_id', 'date', 'window', 'city', 'missing'];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'missing' => 'array',
        ];
    }

    /** @return BelongsTo<SlotJob, $this> */
    public function job(): BelongsTo
    {
        return $this->belongsTo(SlotJob::class, 'slot_job_id');
    }
}
