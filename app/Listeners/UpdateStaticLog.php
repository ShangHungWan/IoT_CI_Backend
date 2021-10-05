<?php

namespace App\Listeners;

use App\Events\ApplicationEmulated;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
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
        foreach (Storage::files("logs/{$uuid}/static/", true) as $file) {
            $filename = explode('/', $file, 4)[3];
            $name = str_split($filename, strlen($filename) - 4)[0];
            $contents = explode("\n", Storage::get($file));

            for ($i = 0; $i < floor(count($contents) / 8); $i++) {
                $this->analysis_repository->createManyStatic($event->analysis, "file/{$uuid}/{$name}", [
                    'line_number' => $contents[8 * $i + 1],
                    'callstack' => $contents[8 * $i + 2],
                    'severity' => $contents[8 * $i + 3],
                    'message' => $contents[8 * $i + 4],
                    'warning_type' => $contents[8 * $i + 5],
                    'code' => $contents[8 * $i + 6],
                ]);
            }
        }
    }
}
