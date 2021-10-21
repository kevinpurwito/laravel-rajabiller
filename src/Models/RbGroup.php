<?php

namespace Kevinpurwito\LaravelRajabiller\Models;

use Illuminate\Database\Eloquent\Model;
use Kevinpurwito\LaravelRajabiller\Relationships\HasManyRbItems;

class RbGroup extends Model
{
    use HasManyRbItems;

    protected $table = 'rb_groups';

    protected $fillable = [
        'is_active', 'type', 'subtype', 'name',
    ];
}
