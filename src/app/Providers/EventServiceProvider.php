<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use App\Providers\EventServiceProvider as ServiceProvider;
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
        
        $events->listen('LoginEvent', 'Solunes\Master\App\Listeners\UserLoggedIn');
        $events->listen('eloquent.created: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\CreatedNode');
        $events->listen('eloquent.deleted: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\DeletedNode');
        $events->listen('eloquent.restored: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\RestoredNode');
        $events->listen('eloquent.created: Solunes\Master\App\Menu', '\Solunes\Master\App\Listeners\CreatingMenu');
        $events->listen('eloquent.created: Solunes\Master\App\Indicator', '\Solunes\Master\App\Listeners\CreatedIndicator');
        $events->listen('eloquent.saved: *', '\Solunes\Master\App\Listeners\RegisterActivityModel');
    }
}
