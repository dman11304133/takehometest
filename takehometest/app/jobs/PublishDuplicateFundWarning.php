<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishDuplicateFundWarning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fund_name;
    protected $fund_manager_name;

    public function __construct($fund_name, $fund_manager_name)
    {
        $this->fund_name = $fund_name;
        $this->fund_manager_name = $fund_manager_name;
    }

    public function handle()
    {
        // Send email notification to data quality team
    }
}
