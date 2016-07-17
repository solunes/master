<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login as LoginEvent;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
        
        $events->listen('LoginEvent', '\Solunes\Master\App\Listeners\UserLoggedIn');
        $events->listen('eloquent.created: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\CreatedNode');
        $events->listen('eloquent.created: Solunes\Master\App\Menu', '\Solunes\Master\App\Listeners\SavedMenu');
        $events->listen('eloquent.saved: *', '\Solunes\Master\App\Listeners\RegisterActivityModel');
    }
}
