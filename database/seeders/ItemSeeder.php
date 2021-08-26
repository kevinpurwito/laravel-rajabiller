<?php

namespace Kevinpurwito\LaravelRajabiller\Database\Seeders;

use Illuminate\Database\Seeder;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;

class ItemSeeder extends Seeder
{
    public function run()
    {
        RbItem::firstOrCreate([]);
    }
}
