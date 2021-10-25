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

        $path = __DIR__ . '/rb_items.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Items table seeded!');

        $query = 'UPDATE rb_items SET rb_group_id = (SELECT id FROM rb_groups WHERE name = rb_items.group_name LIMIT 1)';
        DB::unprepared($query);
        $this->command->info('Group Ids updated!');
    }
}
