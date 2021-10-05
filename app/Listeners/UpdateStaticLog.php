<?php

namespace App\Listeners;

use App\Events\ApplicationEmulated;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateStaticLog
{
    private $analysis_repository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AnalysisRepositoryInterface $repository)
    {
        $this->analysis_repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationEmulated  $event
     * @return void
     */
    public function handle(ApplicationEmulated $event)
    {
        $uuid = $event->analysis->uuid;
        foreach (Storage::disk('log')->files("{$uuid}/static/", true) as $file) {
            Log::debug($file);
            $name = Storage::name($file);
            // str_split($name, strlen($name) - 3)[0]
            // ;
            // Storage::extension($file);
        }
    }
}
