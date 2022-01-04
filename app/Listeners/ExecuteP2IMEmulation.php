<?php

namespace App\Listeners;

use App\Events\BinaryUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ExecuteP2IMEmulation implements ShouldQueue
{
    const TIMEOUT = 1200;

    /**
     * Handle the event.
     *
     * @param BinaryUploaded $event
     * @return void
     */
    public function handle(BinaryUploaded $event)
    {
        $process = new Process([
            'python3',
            config('enum.path.P2IM_SHELL'),
            $event->analysis->uuid,
        ]);
        $process->setTimeout(self::TIMEOUT);
        $process->setIdleTimeout(self::TIMEOUT);
        $process->run();
        Log::debug($process->getOutput());
    }

    /**
     * Determine the time at which the listener should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addSeconds(self::TIMEOUT);
    }
}
