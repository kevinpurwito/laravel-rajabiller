<?php

namespace Kevinpurwito\LaravelRajabiller\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kevinpurwito\LaravelRajabiller\Models\RbGroup;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;

trait BelongsToRbGroup
{
    public function rbGroup(): BelongsTo
    {
        return $this->belongsTo(RbGroup::class, 'rb_group_id');
    }
}
