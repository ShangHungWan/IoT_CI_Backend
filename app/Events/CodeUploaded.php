<?php

namespace App\Events;

use App\Models\Analysis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CodeUploaded
{
    use Dispatchable, SerializesModels;

    public $analysis;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Analysis $analysis)
    {
        $this->analysis = $analysis;
    }
}
