<?php

namespace Kevinpurwito\LaravelRajabiller\Models;

use Illuminate\Database\Eloquent\Model;
use Kevinpurwito\LaravelRajabiller\Relationships\BelongsToRbGroup;
use Kevinpurwito\LaravelRajabiller\Relationships\HasManyRbOrders;

class RbItem extends Model
{
    use BelongsToRbGroup;
    use HasManyRbOrders;

    protected $table = 'rb_items';

    protected $fillable = [
        'rb_group_id',
        'is_active',
        'code',
        'name',
        'type',
        'subtype',
        'group_name',
        'denominator',
        'price',
        'fee',
        'commission',
        'popular',
        'ordinal',
    ];
}
