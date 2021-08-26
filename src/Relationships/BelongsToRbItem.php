<?php

namespace Kevinpurwito\LaravelRajabiller\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;

trait BelongsToRbItem
{
    public function rbItem(): BelongsTo
    {
        return $this->belongsTo(RbItem::class);
    }
}
