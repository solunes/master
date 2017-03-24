<?php

namespace Solunes\Master;

use Illuminate\Support\ServiceProvider;

class MasterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/solunes.php', 'solunes'
        );
    }

    public function boot()
    {
        /* Publicar Elementos */
        $this->publishes([
            __DIR__ . '/config' => config_path()
        ], 'config');
        $this->publishes([
            __DIR__.'/lang-custom' => resource_path('lang/vendor/master'),
        ], 'lang');
        $this->publishes([
            __DIR__.'/assets/admin' => public_path('assets/admin'),
        ], 'assets');

        /* Registrar ServiceProvider Internos */
        $this->app->register('\Solunes\Master\App\Providers\AuthServiceProvider');
        $this->app->register('\Solunes\Master\App\Providers\ComposerServiceProvider');
        $this->app->register('\Solunes\Master\App\Providers\EventServiceProvider');
        $this->app->register('\Solunes\Master\App\Providers\RouteServiceProvider');

        /* Registrar Alias */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('AdminList', '\Solunes\Master\App\Helpers\AdminList');
        $loader->alias('AdminItem', '\Solunes\Master\App\Helpers\AdminItem');
        $loader->alias('Asset', '\Solunes\Master\App\Helpers\Asset');
        $loader->alias('Field', '\Solunes\Master\App\Helpers\Field');
        $loader->alias('Dynamic', '\Solunes\Master\App\Helpers\Dynamic');
        $loader->alias('FuncNode', '\Solunes\Master\App\Helpers\FuncNode');
        $loader->alias('Login', '\Solunes\Master\App\Helpers\Login');

        /* Comandos de Consola */
        $this->commands([
            App\Console\Deploy::class,
            App\Console\EmptyStorage::class,
            App\Console\Seed::class,
            App\Console\GenerateNodes::class,
            App\Console\ImportExcel::class,
        ]);
        
        /* Cargar Traducciones */
        $this->loadTranslationsFrom(__DIR__.'/lang', 'master');

        /* Cargar Vistas */
        $this->loadViewsFrom(__DIR__ . '/views', 'master');
    }
}
