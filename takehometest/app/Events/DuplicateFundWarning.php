<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DuplicateFundWarning
{
    use Dispatchable, SerializesModels;

    public $fundName;
    public $fundManagerName;

    /**
     * Create a new event instance.
     *
     * @param  string  $fundName
     * @param  string  $fundManagerName
     * @return void
     */
    public function __construct($fundName, $fundManagerName)
    {
        $this->fundName = $fundName;
        $this->fundManagerName = $fundManagerName;
    }
}
