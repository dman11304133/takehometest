<?php

namespace App\Listeners;

use App\Events\DuplicateFundWarning;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DuplicateFundWarningListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  DuplicateFundWarning  $event
     * @return void
     */
    public function handle(DuplicateFundWarning $event)
    {
        $fundName = $event->fundName;
        $fundManagerName = $event->fundManagerName;

        // Example action: Log the duplicate fund warning
        Log::info("Duplicate fund warning: Fund '$fundName' with manager '$fundManagerName'");
    }
}
