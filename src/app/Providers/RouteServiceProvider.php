<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Routing\Router;
use App\Providers\RouteServiceProvider as ServiceProvider;

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
        $this->mapWebRoutes($router);
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
        $router->group(['namespace' => $this->solunesNamespace, 'middleware' => 'admin'], function ($router) {
            require __DIR__ . '/../Routes/routes.php';
            require __DIR__ . '/../Routes/sitemap.php';
            require __DIR__ . '/../Routes/artisan.php';
        });
        parent::mapWebRoutes($router);
    }

}
