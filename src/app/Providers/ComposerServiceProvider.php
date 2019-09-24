<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider 
{

    public function boot(ViewFactory $view)
    {
        view()->composer(['layouts.master', 'master::layouts.admin', 'master::layouts.admin-2', 'master::layouts.admin-2-clean'], function ($view) {
            $array['site'] = \Solunes\Master\App\Site::with('translations')->where('id', 1)->first();
            if(auth()->check()){
                $user_id = auth()->user()->id;
                /* Indicadores */
                $array['alerts'] = \Solunes\Master\App\Indicator::get();
                /* Inbox */
                $array['enabled_inbox'] = true;
                if(config('solunes.admin_inbox_disabled')===true||in_array(auth()->user()->role_user()->first()->name, config('solunes.admin_inbox_excluded'))){
                    $array['enabled_inbox'] = false;
                }
                $array['inbox'] = \Solunes\Master\App\Inbox::userInbox($user_id)->with('other_users','last_message')->orderBy('updated_at','DESC')->limit(10)->get();
                $array['inbox_unread_array'] = \Solunes\Master\App\Inbox::userUnreadInbox($user_id)->lists('id')->toArray();
                /* Notifications */
                $array['notifications_unread'] = \Solunes\Master\App\Notification::me()->type('dashboard')->notSent()->orderBy('created_at','DESC')->count();
                    $notifications = \Solunes\Master\App\Notification::me()->type('dashboard');
                if($array['notifications_unread']>0){
                    $notifications = $notifications->notSent();
                    $array['notifications_ids'] = $notifications->orderBy('created_at','DESC')->limit(10)->lists('id');
                } else {
                    $array['notifications_ids'] = [];
                }
                $array['notifications'] = $notifications->orderBy('created_at','DESC')->limit(10)->get();
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
