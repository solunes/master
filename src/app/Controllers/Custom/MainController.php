<?php

namespace Solunes\Master\App\Controllers\Custom;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Segment;

class MainController extends Controller {

	public function __construct() {

  	}

	public function showIndex() {
	    $locale = \App::getLocale();
	    $page = \App\Page::where('customized_name', 'home')->first();
	    return redirect($page->translate($locale)->slug);
	}
  
  	public function showPage($slug) {
	    if($page_translation = \App\PageTranslation::findBySlug($slug)){
	      $page = $page_translation->page;
	      if($page->type!='blank'&&$page->type!='external'&&$page_translation->locale!=\App::getLocale()){
	        return redirect('change-locale/'.$page_translation->locale.'/'.$page_translation->slug);
	      }
	      $array = ['page'=>$page, 'i'=>NULL, 'dt'=>false];
	      if($page->type=='blank'||$page->type=='external'){
	        return abort(404);
	      } 
	      $slug = $page_translation->slug;
	      if(($slug=='postulacion-a'||$slug=='postulacion-b')){
	      	if(!\Auth::check()){
	          return redirect()->guest('auth/login');
	        }
	        if(request()->has('postulation_a')&&$slug=='postulacion-a'){
	       		if(!\Auth::user()->registry_a()->whereHas('postulation_a', function ($query) {
				    $query->where('id', request()->input('postulation_a'));
				    $query->where('status', 'holding');
				})->first()){
	          	  return redirect('postulaciones')->with(['message_error'=>'No tiene acceso para editar este formulario.']);
				}
	        } else if(request()->has('postulation_b')&&$slug=='postulacion-b'){
	       		if(!\Auth::user()->registry_b()->whereHas('postulation_b', function ($query) {
				    $query->where('id', request()->input('postulation_b'));
				    $query->where('status', 'holding');
				})->first()){
	          	  return redirect('postulaciones')->with(['message_error'=>'No tiene acceso para editar este formulario.']);
				}
	        } else {
	          return redirect('postulaciones')->with(['message_error'=>'Hubo un error al realizar su consulta.']);
	        }
	      } 
	      foreach($page->nodes as $node){
	        $array = \Segment::get_node_array($array, $node, $page);
	      }
	      $array = array_merge($array, \CustomFunc::get_page_array($page));
	      if($page->type=='customized') {
	        if($page->customized_name=='home'){
	          $array['social_networks'] = \App\SocialNetwork::get();
	        } else if($page->customized_name=='postulaciones'){
	          $array['registry_a'] = \Auth::user()->registry_a;
	          $array['registry_b'] = \Auth::user()->registry_b;
	        }
	        return view('content.'.$page->customized_name, $array);
	      } else {
	        return view('content.page', $array);
	      }
	    } else {
	      return abort(404);
	    }
  	}
  
}