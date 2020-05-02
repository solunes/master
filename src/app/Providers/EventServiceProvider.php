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
        
        if('solunes.business'){
            $events->listen('eloquent.created: Solunes\Business\App\Company', '\Solunes\Business\App\Listeners\CompanyCreated');
            $events->listen('eloquent.created: Solunes\Business\App\Contact', '\Solunes\Business\App\Listeners\ContactCreated');
            $events->listen('eloquent.created: Solunes\Business\App\Deal', '\Solunes\Business\App\Listeners\DealCreated');
            $events->listen('eloquent.saving: Solunes\Business\App\ProductBridge', '\Solunes\Business\App\Listeners\ProductBridgeSaving');
            $events->listen('eloquent.creating: Solunes\Business\App\Agency', '\Solunes\Business\App\Listeners\AgencyCreating');
        }
        if('solunes.customer'){
            $events->listen('eloquent.saving: Solunes\Customer\App\Customer', '\Solunes\Customer\App\Listeners\CustomerSaving');
            $events->listen('eloquent.created: Solunes\Customer\App\CustomerContact', '\Solunes\Customer\App\Listeners\CustomerContactCreated');
            $events->listen('eloquent.creating: Solunes\Customer\App\CustomerContact', '\Solunes\Customer\App\Listeners\CustomerContactCreating');
            $events->listen('eloquent.updating: Solunes\Customer\App\CustomerContact', '\Solunes\Customer\App\Listeners\CustomerContactUpdating');
            $events->listen('eloquent.creating: Solunes\Customer\App\CustomerNote', '\Solunes\Customer\App\Listeners\CustomerNoteCreating');
            $events->listen('eloquent.created: Solunes\Customer\App\CustomerNote', '\Solunes\Customer\App\Listeners\CustomerNoteCreated');
            $events->listen('eloquent.creating: Solunes\Customer\App\CustomerPayment', '\Solunes\Customer\App\Listeners\CustomerPaymentCreating');
            // Suscripciones
            $events->listen('eloquent.creating: Solunes\Customer\App\CustomerSubscription', '\Solunes\Customer\App\Listeners\CustomerSubscriptionCreating');
            $events->listen('eloquent.created: Solunes\Customer\App\CustomerSubscription', '\Solunes\Customer\App\Listeners\CustomerSubscriptionCreated');
            $events->listen('eloquent.creating: Solunes\Customer\App\CustomerSubscriptionMonth', '\Solunes\Customer\App\Listeners\CustomerSubscriptionMonthCreating');
            $events->listen('eloquent.saving: Solunes\Customer\App\CustomerSubscriptionMonth', '\Solunes\Customer\App\Listeners\CustomerSubscriptionMonthSaving');
            $events->listen('eloquent.saved: Solunes\Customer\App\SubscriptionPlan', '\Solunes\Customer\App\Listeners\SubscriptionPlanSaved');
            $events->listen('eloquent.saved: Solunes\Customer\App\Ppv', '\Solunes\Customer\App\Listeners\PpvSaved');
        }
        if('solunes.payments'){
            $events->listen('eloquent.saved: Solunes\Payments\App\TransactionPayment', '\Solunes\Payments\App\Listeners\TransactionPaymentSaved');
        }
        if('solunes.product'){
            $events->listen('eloquent.saved: Solunes\Product\App\Product', '\Solunes\Product\App\Listeners\ProductSaved');
            $events->listen('eloquent.saving: Solunes\Product\App\Product', '\Solunes\Product\App\Listeners\ProductSaving');
        }
        if('solunes.inventory'){
            $events->listen('eloquent.created: Solunes\Inventory\App\StockAddition', '\Solunes\Inventory\App\Listeners\StockAdditionCreated');
            $events->listen('eloquent.created: Solunes\Inventory\App\StockTransfer', '\Solunes\Inventory\App\Listeners\StockTransferCreated');
            $events->listen('eloquent.created: Solunes\Inventory\App\StockRemoval', '\Solunes\Inventory\App\Listeners\StockRemovalCreated');
        }
        if('solunes.reservation'){
            $events->listen('eloquent.saved: Solunes\Reservation\App\Accommodation', '\Solunes\Reservation\App\Listeners\AccommodationSaved');
        }
        if('solunes.sales'){
            $events->listen('eloquent.updating: Solunes\Sales\App\Sale', '\Solunes\Sales\App\Listeners\SaleUpdating');
        }
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
