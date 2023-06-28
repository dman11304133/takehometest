<?php

namespace App\Jobs;

use App\Models\Fund;
use App\Models\Alias;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateFundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fundData;
    protected $aliases;

    /**
     * Create a new job instance.
     *
     * @param  array  $fundData
     * @param  array  $aliases
     * @return void
     */
    public function __construct(array $fundData, array $aliases)
    {
        $this->fundData = $fundData;
        $this->aliases = $aliases;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fund = new Fund();
        $fund->fill($this->fundData);

        try {
            $fund->save();
            Log::info('Fund saved successfully: ' . $fund->id);
        } catch (\Exception $e) {
            Log::error('Error saving fund: ' . $e->getMessage());
        }

        $this->saveAliases($fund, $this->aliases);
    }


    private function saveAliases(Fund $fund, array $aliasNames)
    {
        $aliases = [];
        foreach ($aliasNames as $aliasName) {
            $alias = Alias::firstOrCreate(['name' => $aliasName]);
            $aliases[] = $alias;
        }

        $fund->aliases()->saveMany($aliases);
    }
}
