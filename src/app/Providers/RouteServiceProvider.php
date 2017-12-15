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
    protected $projectNamespace = 'Solunes\Project\App\Controllers';
    protected $storeNamespace = 'Solunes\Store\App\Controllers';

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
        if(config('solunes.project')){
            $router->group(['namespace' => $this->projectNamespace, 'middleware' => 'admin'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/admin.php';
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
        if(config('solunes.project')){
            $router->group(['namespace' => $this->projectNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/routes.php';
            });
        }
        /*if(config('solunes.store')){
            $router->group(['namespace' => $this->storeNamespace, 'middleware' => 'web'], function ($router) {
                require __DIR__ . '/../../../../project/src/app/Routes/routes.php';
            });
        }*/
    }

}
