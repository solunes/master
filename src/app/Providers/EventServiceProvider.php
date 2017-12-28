<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        
        $events->listen('Illuminate\Auth\Events\Login', 'Solunes\Master\App\Listeners\UserLoggedIn');
        $events->listen('eloquent.created: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\CreatedNode');
        $events->listen('eloquent.deleted: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\DeletedNode');
        $events->listen('eloquent.restored: Solunes\Master\App\Node', '\Solunes\Master\App\Listeners\RestoredNode');
        $events->listen('eloquent.created: Solunes\Master\App\Menu', '\Solunes\Master\App\Listeners\CreatingMenu');
        $events->listen('eloquent.created: Solunes\Master\App\Indicator', '\Solunes\Master\App\Listeners\CreatedIndicator');
        $events->listen('eloquent.saved: App\User', '\Solunes\Master\App\Listeners\UserSaved');
        $events->listen('eloquent.saved: *', '\Solunes\Master\App\Listeners\RegisterActivityModel');
        
        parent::boot($events);
    }
}
