<?php

namespace Kevinpurwito\LaravelRajabiller\Models;

use Illuminate\Database\Eloquent\Model;
use Kevinpurwito\LaravelRajabiller\Relationships\BelongsToRbItem;

class RbOrder extends Model
{
    use BelongsToRbItem;

    protected $table = 'rb_orders';

    protected $fillable = [
        'rb_item_id',
        'code',
        'sn',
        'uid',
        'env',
        'status',
        'amount',
        'item_code',
        'time',
        'customer_id_1',
        'customer_id_2',
        'customer_id_3',
        'customer_name',
        'period',
        'ref_1',
        'ref_2',
        'ref_3',
        'receipt_url',
        'note',
        'detail',
        'balance_deducted',
        'balance_remaining',
    ];
}
