<?php

namespace App\Listeners;

use App\Events\FileUploaded;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExecuteAnalyses implements ShouldQueue
{
    const FAILED = 'failed';
    const SUCCESS = 'success';
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
     * @param FileUploaded $event
     * @return void
     */
    public function handle(FileUploaded $event)
    {
        $process = new Process(['sudo ' . config('iotci.path.STATIC_ANALYSIS_SHELL'), $event->analysis->uuid, $event->analysis->device_id, base_path('storage/app/file'), '/home/ubuntu/Logs']);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('UUID: ' . $event->analysis->uuid . '\'s static analysis failed.');
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        Log::debug('[' . $event->analysis->uuid . ']:' . $output);

        $this->analysis_repository->update($event->analysis, [
            'status' => $this->getStatus($output),
        ]);

        if (self::SUCCESS === $output) {
            // trigger event to search file and insert records
        }
    }

    private function getStatus(string $message)
    {
        if (self::FAILED === $message) {
            return config('iotci.analysis.status.EMULATE_FAILED');
        } else if (self::SUCCESS === $message) {
            return config('iotci.analysis.status.TESTING');
        }
    }
}
