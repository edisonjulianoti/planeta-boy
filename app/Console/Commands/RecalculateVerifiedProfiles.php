<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Observers\ProfileObserver;
use Illuminate\Console\Command;

class RecalculateVerifiedProfiles extends Command
{
    protected $signature = 'profiles:recalculate-verified';
    protected $description = 'Recalculate verified status for all non-manual profiles';

    public function handle(): int
    {
        $this->info('Recalculating verified status...');

        $count = ProfileObserver::recalculateAll();

        $this->info("Done! {$count} profiles checked.");

        return Command::SUCCESS;
    }
}
