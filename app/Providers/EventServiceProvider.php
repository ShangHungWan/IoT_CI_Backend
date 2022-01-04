<?php

namespace App\Providers;

use App\Events\ApplicationEmulated;
use App\Events\BinaryUploaded;
use App\Events\CodeUploaded;
use App\Listeners\ExecuteAnalyses;
use App\Listeners\ExecuteP2IMEmulation;
use App\Listeners\UpdateStaticLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CodeUploaded::class => [
            ExecuteAnalyses::class,
        ],
        BinaryUploaded::class => [
            ExecuteP2IMEmulation::class,
        ],
        ApplicationEmulated::class => [
            UpdateStaticLog::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
