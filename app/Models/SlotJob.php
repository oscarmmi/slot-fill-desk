<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** @property-read SlotFill|null $slotFill */
class SlotJob extends Model
{
    protected $fillable = ['source_text', 'status'];

    /** @return HasOne<SlotFill, $this> */
    public function slotFill(): HasOne
    {
        return $this->hasOne(SlotFill::class);
    }
}
