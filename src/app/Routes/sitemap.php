<?php

Route::get('sitemap', function(){

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
                $sitemap->add($page->translate()->slug, $page->created_at, $priority, 'daily');
            }
            $node_array = \CustomFunc::getSitemapArray($lang->code);
            foreach($node_array as $node_key => $node_item){
                $node = \Solunes\Master\App\Node::where('name',$node_key)->first();
                $node_model = $node->model;
                foreach($node_model::orderBy('created_at','desc')->get() as $post){
                    $sitemap->add($node_item['url'].$post->$node_item['url_id'], $post->created_at, $node_item['priority'], 'monthly');
                }
            }
        }
    }
    return $sitemap->render('xml');

});