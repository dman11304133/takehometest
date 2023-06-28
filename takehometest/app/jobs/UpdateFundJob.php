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

class UpdateFundJob implements ShouldQueue
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
        $fund = Fund::findOrFail($this->fundData['id']);
        $fund->fill($this->fundData);

        if ($this->isDuplicateFund($fund)) {
            $this->handleDuplicateFundWarning($fund->name, $fund->manager->name);
            session()->flash('message', 'Duplicate fund detected!');
        }

        $fund->save();
        $this->saveAliases($fund, $this->aliases);
    }

    private function isDuplicateFund(Fund $fund): bool
    {
        return Fund::where('id', '<>', $fund->id)
            ->where('manager_id', $fund->manager_id)
            ->where(function ($query) use ($fund) {
                $query->where('name', $fund->name)
                    ->orWhereHas('aliases', function ($query) use ($fund) {
                        $query->where('name', $fund->name);
                    });
            })->exists();
    }

    private function handleDuplicateFundWarning($fundName, $fundManagerName)
    {
        Log::info("Duplicate fund warning: Fund '$fundName' with manager '$fundManagerName'");
    }

    private function saveAliases(Fund $fund, array $aliasNames)
    {
        $aliases = [];
        foreach ($aliasNames as $aliasName) {
            $alias = Alias::firstOrCreate(['name' => $aliasName]);
            $aliases[] = $alias;
        }

        $fund->aliases()->sync($aliases);
    }
}
