<?php

namespace Kevinpurwito\LaravelRajabiller\Relationships;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Kevinpurwito\LaravelRajabiller\Models\RbOrder;

trait HasManyRbOrders
{
    public function rbOrders(): HasMany
    {
        return $this->hasMany(RbOrder::class, 'rb_item_id');
    }
}
