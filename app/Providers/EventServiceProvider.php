<?php

namespace App\Providers;

use App\Events\ApplicationEmulated;
use App\Events\FileUploaded;
use App\Listeners\ExecuteAnalyses;
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
        FileUploaded::class => [
            ExecuteAnalyses::class,
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
