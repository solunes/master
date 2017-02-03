<?php

Route::get('sitemap.xml', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    $sitemap->setCache('laravel.sitemap', 3600);
    if (!$sitemap->isCached()) {
        foreach(\Solunes\Master\App\Language::get() as $lang){
            \App::setLocale($lang->code);
            foreach(\Solunes\Master\App\Page::get() as $page){
                if($page->id==1){
                    $priority = '1.0';
                } else {
                    $priority = '0.9';
                }
                $sitemap->add(URL::to($page->translate($lang->code)->slug), $page->created_at, $priority, 'daily');
            }
            if(config('solunes.get_sitemap_array')){
                $node_array = \CustomFunc::get_sitemap_array($lang->code);
                if(count($node_array)>0){
                    foreach($node_array as $node_key => $node_item){
                        $node = \Solunes\Master\App\Node::where('name',$node_key)->first();
                        $node_model = $node->model;
                        foreach($node_model::orderBy('created_at','desc')->get() as $post){
                            $sitemap->add(URL::to($node_item['url'].$post->$node_item['url_id']), $post->created_at, $node_item['priority'], 'monthly');
                        }
                    }
                }
            }
        }
    }
    return $sitemap->render('xml');

});