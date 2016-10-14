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
      if($request->segment(1)=='auth'){
        Menu::make('main', function($menu) {
          $menu->add('Ingresar', 'auth/login');
        });
      } else {
        $menu_includes = ['translations', 'page', 'page.translations', 'children', 'children.translations', 'children.page', 'children.page.translations', 'children.children', 'children.children.translations', 'children.children.page', 'children.children.page.translations'];
        if(\Func::check_layout()=='admin'){
          $menu_options = \Solunes\Master\App\Menu::where('site_id', 1)->where('menu_type', 'admin')->where('level', 1)->with($menu_includes)->orderBy('order','ASC')->get();
        } else {
          $menu_options = \Solunes\Master\App\Menu::where('site_id', 1)->where('menu_type', 'site')->where('level', 1)->with($menu_includes)->orderBy('order','ASC')->get();
        }
        Menu::make('main', function($menu) use($request, $menu_options, $user_permissions) {
          foreach($menu_options as $menu_option){
            if(!$menu_option->permission||(auth()->check()&&$user_permissions->contains($menu_option->permission))){
              $first_level = $menu->add($menu_option->name, $menu_option->real_link);
              if(\Func::check_layout()=='admin'){
                $first_level->prepend('<span class="title">');
                if($menu_option->icon){
                  $first_level->prepend('<i class="fa fa-'.$menu_option->icon.'"></i>');
                }
                $first_level->append('</span>');
              }
              if(count($menu_option->children)>0){
                if(\Func::check_layout()=='admin'){
                  $first_level->append('<span class="arrow"></span>');
                } else {
                  $first_level->prepend('<span class="fa fa-caret-down"></span> ');
                  $first_level->attribute(['class' => 'dropdown']);
                }
                foreach($menu_option->children as $menu_children){
                  if(!$menu_children->permission||(auth()->check()&&$user_permissions->contains($menu_children->permission))){
                    $second_level = $first_level->add($menu_children->name, $menu_children->real_link);
                    if(\Func::check_layout()=='admin'){
                      $second_level->attribute(['class' => 'nav-link']);
                      if($menu_children->icon){
                        $second_level->prepend('<i class="fa fa-'.$menu_children->icon.'"></i>');
                      }
                    }
                    if(count($menu_children->children)>0){
                      if(\Func::check_layout()=='admin'){
                        $second_level->append('<span class="arrow"></span>');
                      } else {
                        $second_level->prepend('<span class="fa fa-caret-down"></span> ');
                        $second_level->attribute(['class' => 'dropdown']);
                      }
                      foreach($menu_children->children as $menu_children2){
                        $third_level = $second_level->add($menu_children2->name, $menu_children2->real_link);
                        if(\Func::check_layout()=='admin'){
                          $third_level->attribute(['class' => 'nav-link']);
                          if($menu_children2->icon){
                            $third_level->prepend('<i class="fa fa-'.$menu_children2->icon.'"></i>');
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        });
      }
      return $next($request);
    }
}