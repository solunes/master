<?php

namespace Solunes\Master;

use Illuminate\Support\ServiceProvider;

class MasterServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot() {
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

        /* Cargar Traducciones */
        $this->loadTranslationsFrom(__DIR__.'/lang', 'master');

        /* Cargar Vistas */
        $this->loadViewsFrom(__DIR__ . '/views', 'master');
    }


    public function register() {
        /* Registrar ServiceProvider Internos */
        $this->app->register('Collective\Html\HtmlServiceProvider');
        $this->app->register('Bogardo\Mailgun\MailgunServiceProvider');
        $this->app->register('Caffeinated\Menus\MenusServiceProvider');
        $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
        $this->app->register('Dimsav\Translatable\TranslatableServiceProvider');
        $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
        $this->app->register('Barryvdh\Snappy\ServiceProvider');
        $this->app->register('SimpleSoftwareIO\QrCode\QrCodeServiceProvider');
        $this->app->register('Intervention\Image\ImageServiceProvider');
        $this->app->register('Laravel\Socialite\SocialiteServiceProvider');
        $this->app->register('Anhskohbo\NoCaptcha\NoCaptchaServiceProvider');
        $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        $this->app->register('Spatie\Backup\BackupServiceProvider');

        /* Registrar Alias */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('HTML', 'Collective\Html\HtmlFacade');
        $loader->alias('Form', 'Collective\Html\FormFacade');
        $loader->alias('Master', 'Solunes\Master\MasterFacade');
        $loader->alias('Mailgun', 'Bogardo\Mailgun\Facades\Mailgun');
        $loader->alias('Menu', 'Caffeinated\Menus\Facades\Menu');
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $loader->alias('PDF', 'Barryvdh\Snappy\Facades\SnappyPdf');
        $loader->alias('PDFImage', 'Barryvdh\Snappy\Facades\SnappyImage');
        $loader->alias('Image', 'Intervention\Image\Facades\Image');
        $loader->alias('QrCode', 'SimpleSoftwareIO\QrCode\Facades\QrCode');
        $loader->alias('BarcodeGenerator', 'CodeItNow\BarcodeBundle\Utils\BarcodeGenerator');
        $loader->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
        $loader->alias('NoCaptcha', 'Anhskohbo\NoCaptcha\Facades\NoCaptcha');
        $loader->alias('Socialite', 'Laravel\Socialite\Facades\Socialite');
        $loader->alias('WebPConvert', 'WebPConvert\WebPConvert');

        $loader->alias('AdminList', '\Solunes\Master\App\Helpers\AdminList');
        $loader->alias('AdminItem', '\Solunes\Master\App\Helpers\AdminItem');
        $loader->alias('Asset', '\Solunes\Master\App\Helpers\Asset');
        $loader->alias('Field', '\Solunes\Master\App\Helpers\Field');
        $loader->alias('Dynamic', '\Solunes\Master\App\Helpers\Dynamic');
        $loader->alias('FuncNode', '\Solunes\Master\App\Helpers\FuncNode');
        $loader->alias('Login', '\Solunes\Master\App\Helpers\Login');
        $loader->alias('DataManager', '\Solunes\Master\App\Helpers\DataManager');
        $loader->alias('External', '\Solunes\Master\App\Helpers\External');
        $loader->alias('Reports', '\Solunes\Master\App\Helpers\Reports');
        $loader->alias('SolunesFunc', '\Solunes\Master\App\Helpers\SolunesFunc');

        /* Comandos de Consola */
        $this->commands([
            \Solunes\Master\App\Console\Deploy::class,
            \Solunes\Master\App\Console\EmptyStorage::class,
            \Solunes\Master\App\Console\Seed::class,
            \Solunes\Master\App\Console\GenerateNodes::class,
            \Solunes\Master\App\Console\ImportExcel::class,
            \Solunes\Master\App\Console\GenerateTranslations::class,
            \Solunes\Master\App\Console\TestSystem::class,
            \Solunes\Master\App\Console\BackupSave::class,
            \Solunes\Master\App\Console\WebpAllFiles::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/solunes.php', 'solunes'
        );
    }
    
}
