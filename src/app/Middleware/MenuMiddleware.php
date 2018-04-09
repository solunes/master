<?php

namespace Solunes\Master\App\Middleware;

use Closure;
use Menu;
use Auth;

class MenuMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
      if(auth()->check()&&auth()->user()->isSuperAdmin()){
        $user_permissions = \Solunes\Master\App\Permission::lists('name');
      } else if(auth()->check()){
        $user_permissions = auth()->user()->getPermission();
      } else {
        $user_permissions = [];
      }
      if(!auth()->check()){
        Menu::make('main', function($menu) {
          $login = $menu->add('Ingresar', 'auth/login');
          $login = $menu->add('Olvidaste tu contraseña?', 'password/recover');
          if(config('solunes.login_instructions')){
            $login = $menu->add('Instrucciones', 'auth/info');
          }
        });
      } else {
        $menu_options = \Solunes\Master\App\Menu::where('site_id', 1)->isActive()->menuQuery('admin', 1)->get();
        Menu::make('main', function($menu) use($request, $menu_options, $user_permissions) {
          foreach($menu_options as $menu_option){
            if(!$menu_option->permission||(auth()->check()&&$user_permissions->contains($menu_option->permission))){
              $first_level = $menu->add($menu_option->name, $menu_option->real_link);
              /*if($menu_option->icon){
                $first_level->prepend('<i class="fa fa-'.$menu_option->icon.'"></i>');
              }*/
              $first_level->prepend('<span class="m-menu__link-text">');
              $first_level->append('</span>');
              if(config('solunes.admin_menu_extras')){
                if(in_array($menu_option->link, config('solunes.admin_menu_extra_array'))){
                  \CustomFunc::admin_menu_extras($menu_option, $first_level, 1);
                }
              }
              if(count($menu_option->children)>0){
                //$first_level->append('<span class="arrow"></span>');
                $first_level->attribute(['class' => 'nav-link nav-toggle']);
                foreach($menu_option->children as $menu_children){
                  if(!$menu_children->permission||(auth()->check()&&$user_permissions->contains($menu_children->permission))){
                    $second_level = $first_level->add($menu_children->name, $menu_children->real_link);
                    if($menu_children->icon){
                      $second_level->prepend('<i class="fa fa-'.$menu_children->icon.'"></i>');
                    }
                    $second_level->prepend('<span class="m-menu__link-text">');
                    $second_level->append('</span>');
                    if(config('solunes.admin_menu_extras')){
                      if(in_array($menu_children->link, config('solunes.admin_menu_extra_array'))){
                        \CustomFunc::admin_menu_extras($menu_children, $second_level, 2);
                      }
                    }
                    if(count($menu_children->children)>0){
                      //$second_level->append('<span class="arrow"></span>');
                      //$second_level->attribute(['class' => 'nav-link nav-toggle']);
                      foreach($menu_children->children as $menu_children2){
                        $third_level = $second_level->add($menu_children2->name, $menu_children2->real_link);
                        //$third_level->attribute(['class' => 'nav-link']);
                        if($menu_children2->icon){
                          //$third_level->prepend('<i class="fa fa-'.$menu_children2->icon.'"></i>');
                        }
                      }
                    } else {
                      //$second_level->attribute(['class' => 'nav-link']);
                    }
                  }
                }
              } else {
                $first_level->attribute(['class' => 'nav-link']);
              }
            }
          }
        });
      }
      return $next($request);
    }
}