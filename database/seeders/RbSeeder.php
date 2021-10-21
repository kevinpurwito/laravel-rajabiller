<?php

namespace Kevinpurwito\LaravelRajabiller\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbSeeder extends Seeder
{
    public function run()
    {
        $path = __DIR__ . '/rb_groups.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Groups table seeded!');

        $path = __DIR__ . '/rb_groups.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Items table seeded!');
    }
}
