<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider 
{

    public function boot(ViewFactory $view)
    {
        view()->composer('layouts.master', function ($view) {
            $array['site'] = \Solunes\Master\App\Site::with('translations')->where('id', 1)->first();
            if(request()->has('download-pdf')){
                $array['pdf'] = true;
            } else {
                $array['pdf'] = false;
            }
            $view->with($array);
        });
    }
    
    public function register()
    {
        //
    }

}
