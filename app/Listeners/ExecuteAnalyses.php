<?php

namespace App\Listeners;

use App\Events\ApplicationEmulated;
use App\Events\FileUploaded;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExecuteAnalyses implements ShouldQueue
{
    const EMLATION_FAILED = 'emulation_failed';
    const COMPILATION_FAILED = 'compilation_failed';
    const SUCCESS = 'success';

    public $timeout = 600;

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
        $process = new Process([
            'sudo',
            config('iotci.path.STATIC_ANALYSIS_SHELL'),
            $event->analysis->uuid,
            $event->analysis->device_id,
            base_path('storage/app/file'),
            config('iotci.path.LOG_FOLDER'),
        ]);
        $process->setTimeout(600);
        $process->setIdleTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('UUID: ' . $event->analysis->uuid . '\'s static analysis failed.');
            $this->analysis_repository->update($event->analysis, [
                'status' => config('iotci.analysis.status.EMULATION_FAILED'),
            ]);
            throw new ProcessFailedException($process);
        }

        $output = trim($process->getOutput());
        Log::debug('[' . $event->analysis->uuid . ']:' . $output);

        $this->analysis_repository->update($event->analysis, [
            'status' => $this->getStatus($output),
        ]);

        if (self::SUCCESS === $output) {
            ApplicationEmulated::dispatch($event->analysis);
        }
    }

    private function getStatus(string $message)
    {
        if (self::EMLATION_FAILED === $message) {
            return config('iotci.analysis.status.EMULATION_FAILED');
        } else if (self::COMPILATION_FAILED === $message) {
            return config('iotci.analysis.status.COMPILATION_FAILED');
        } else if (self::SUCCESS === $message) {
            return config('iotci.analysis.status.TESTING');
        } else {
            return config('iotci.analysis.status.EMULATION_FAILED');
        }
    }
}
