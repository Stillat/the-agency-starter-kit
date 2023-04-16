<?php

namespace App\Providers;

use App\Listeners\BlueprintSavingListener;
use App\Listeners\MetaBlueprintFieldsListener;
use App\Listeners\ResponseCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Statamic\Events\BlueprintSaving;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Events\ResponseCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ResponseCreated::class => [
            ResponseCreatedListener::class,
        ],
        EntryBlueprintFound::class => [
            MetaBlueprintFieldsListener::class,
        ],
        BlueprintSaving::class => [
            BlueprintSavingListener::class,
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
