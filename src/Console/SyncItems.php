<?php


namespace Kevinpurwito\LaravelRajabiller\Console;

use Illuminate\Console\Command;
use Kevinpurwito\LaravelRajabiller\Facades\Rajabiller;

class SyncItems extends Command
{
    protected $signature = 'rb-sync-items';

    protected $description = 'Syncing Rajabiller item list, price & availability';

    public function handle()
    {
        Rajabiller::populateItems();
    }
}
