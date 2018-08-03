<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $solunesNamespace = 'Solunes\Master\App\Controllers';
    protected $businessNamespace = 'Solunes\Business\App\Controllers';
    protected $projectNamespace = 'Solunes\Project\App\Controllers';
    protected $salesNamespace = 'Solunes\Sales\App\Controllers';
    protected $productNamespace = 'Solunes\Product\App\Controllers';
    protected $inventoryNamespace = 'Solunes\Inventory\App\Controllers';
    protected $paymentsNamespace = 'Solunes\Payments\App\Controllers';
    protected $storeNamespace = 'Solunes\Store\App\Controllers';
    protected $pagosttNamespace = 'Solunes\Pagostt\App\Controllers';
    protected $notificationNamespace = 'Solunes\Notification\App\Controllers';
    protected $todotixCustomerNamespace = 'Todotix\Customer\App\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        //$this->mapWebRoutes($router);
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapAdminRoutes(Router $router)
    {
        $router->group(['namespace' => $this->solunesNamespace, 'middleware' => 'admin'], function ($router) {
            require __DIR__ . '/../Routes/routes.php';
            require __DIR__ . '/../Routes/sitemap.php';
            require __DIR__ . '/../Routes/artisan.php';
        });
        if(config('solunes.business')){
            $router->group(['namespace' => $this->businessNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../business/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.project')){
            $router->group(['namespace' => $this->projectNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.sales')){
            $router->group(['namespace' => $this->salesNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../sales/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.product')){
            $router->group(['namespace' => $this->productNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../product/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.inventory')){
            $router->group(['namespace' => $this->inventoryNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../inventory/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.payments')){
            $router->group(['namespace' => $this->paymentsNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../payments/src/app/Routes/admin.php';
            });
        }
        if(config('solunes.todotix-customer')){
            $router->group(['namespace' => $this->todotixCustomerNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../../todotix/customer/src/app/Routes/admin.php';
            });
        }
        /*if(config('solunes.store')){
            $router->group(['namespace' => $this->storeNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/admin.php';
            });
        }*/
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        if(config('solunes.business')){
            $router->group(['namespace' => $this->businessNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../business/src/app/Routes/routes.php';
            });
        }
        if(config('solunes.project')){
            $router->group(['namespace' => $this->projectNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/routes.php';
            });
        }
        if(config('solunes.sales')){
            $router->group(['namespace' => $this->salesNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../sales/src/app/Routes/routes.php';
            });
        }
        if(config('solunes.product')){
            $router->group(['namespace' => $this->productNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../product/src/app/Routes/routes.php';
            });
        }
        if(config('solunes.inventory')){
            $router->group(['namespace' => $this->inventoryNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../inventory/src/app/Routes/routes.php';
            });
        }
        if(config('solunes.payments')){
            $router->group(['namespace' => $this->paymentsNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../payments/src/app/Routes/routes.php';
                require __DIR__ . '/../../../../payments/src/app/Routes/api.php';
            });
        }
        /*if(config('solunes.store')){
            $router->group(['namespace' => $this->storeNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/routes.php';
            });
        }*/
        if(config('solunes.pagostt')){
            $router->group(['namespace' => $this->pagosttNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../pagostt/src/app/Routes/routes.php';
                require __DIR__ . '/../../../../pagostt/src/app/Routes/api.php';
            });
        }
        if(config('solunes.todotix-customer')){
            $router->group(['namespace' => $this->todotixCustomerNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../../todotix/customer/src/app/Routes/routes.php';
            });
        }
    }

}
