<?php

namespace App\Listeners;

use App\Events\ApplicationEmulated;
use App\Events\FileUploaded;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExecuteAnalyses implements ShouldQueue
{
    const FAILED = 'failed';
    const EMULATION_FAILED = 'emulation_failed';
    const COMPILATION_FAILED = 'compilation_failed';
    const SUCCESS = 'success';
    const TIMEOUT = 600;

    public $timeout = self::TIMEOUT;

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
            base_path("storage/app/public/execution_files"),
        ]);
        $process->setTimeout(self::TIMEOUT);
        $process->setIdleTimeout(self::TIMEOUT);
        $process->run();

        if (!$process->isSuccessful() && $process->getErrorOutput() != self::SUCCESS) {
            Log::error('UUID: ' . $event->analysis->uuid . ' ' . $process->getErrorOutput());

            $dynamic_status = self::FAILED;
            $static_status = null;

            if ($process->getErrorOutput() === self::EMULATION_FAILED) {
            } else if ($process->getErrorOutput() === self::COMPILATION_FAILED) {
                $static_status = self::FAILED;
            } else {
                $static_status = self::FAILED;
            }

            $this->analysis_repository->update($event->analysis, [
                'dynamic_status' => $dynamic_status,
                'static_status' => $static_status,
            ]);
            throw new ProcessFailedException($process);
        }

        $output = trim($process->getOutput());
        Log::debug('[' . $event->analysis->uuid . ']:' . $output);

        $execution_path = null;

        if (self::SUCCESS === $output) {
            $path = "public/execution_files/{$event->analysis->uuid}";
            if (Storage::exists($path)) {
                $execution_path = $path;
            }
            ApplicationEmulated::dispatch($event->analysis);
        }

        $status = $this->getStatus($output);
        $this->analysis_repository->update($event->analysis, [
            'static_status' => $status['static_status'],
            'dynamic_status' => $status['dynamic_status'],
            'execution_path' => $execution_path,
        ]);
    }

    private function getStatus(string $message)
    {
        if (self::EMULATION_FAILED === $message) {
            return ['dynamic_status' => self::FAILED, 'static_status' => self::SUCCESS];
        } else if (self::COMPILATION_FAILED === $message) {
            return ['dynamic_status' => self::FAILED, 'static_status' => self::FAILED];
        } else if (self::SUCCESS === $message) {
            return ['dynamic_status' => config('iotci.analysis.status.TESTING'), 'static_status' => config('iotci.analysis.status.SUCCESS')];
        } else {
            return ['dynamic_status' => self::FAILED, 'static_status' => self::FAILED];
        }
    }
}
