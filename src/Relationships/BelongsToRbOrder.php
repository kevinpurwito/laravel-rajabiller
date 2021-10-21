<?php

namespace Kevinpurwito\LaravelRajabiller\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kevinpurwito\LaravelRajabiller\Models\RbOrder;

trait BelongsToRbOrder
{
    public function rbOrder(): BelongsTo
    {
        return $this->belongsTo(RbOrder::class, 'rb_order_id');
    }
}
