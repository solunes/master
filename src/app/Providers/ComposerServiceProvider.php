<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use App\Providers\ComposerServiceProvider as ServiceProvider;

class ComposerServiceProvider extends ServiceProvider 
{

    public function boot(ViewFactory $view)
    {
        parent::boot($view);
        view()->composer(['layouts.master', 'master::layouts.admin'], function ($view) {
            $array['site'] = \Solunes\Master\App\Site::with('translations')->where('id', 1)->first();
            if(auth()->check()){
                $array['alerts'] = \Solunes\Master\App\IndicatorAlert::where('user_id', auth()->user()->id)->with('indicator','indicator.indicator_values')->get();
                $array['inbox'] = \Solunes\Master\App\Inbox::whereHas('inbox_users', function($q){
                    $q->where('user_id', auth()->user()->id);
                })->with('other_users','last_message')->orderBy('updated_at','DESC')->limit(10)->get();
                $array['notifications'] = \Solunes\Master\App\Notification::where('user_id', auth()->user()->id)->orderBy('created_at','DESC')->limit(10)->get();
            }
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
