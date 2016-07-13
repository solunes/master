<?php

namespace Solunes\Master\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Asset;

class AssetController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->prev = $url->previous();
	}

    public function postFroalaImageUpload(Request $request) {
	  if($request->hasFile('file')) {
	    $new_file = Asset::upload_image($request->file('file'), 'upload');
	    $get_file = Asset::get_image_path('upload', 'normal', $new_file);
        $response = new \StdClass;
        $response->link = $get_file;
        return stripslashes(json_encode($response));
	  } else {
        return \Response::json('error', 400);
	  }
    }

    public function postFroalaFileUpload(Request $request) {
	  if($request->hasFile('file')) {
	    $new_file = Asset::upload_file($request->file('file'), 'upload-file');
	    $get_file = Asset::get_file($new_file, 'upload-file');
        $response = new \StdClass;
        $response->link = $get_file;
        return stripslashes(json_encode($response));
	  } else {
        return \Response::json('error', 400);
	  }
    }

    public function postUpload(Request $request) {
      $error = false;
      $filesize = 5;
      $image_array = ['jpg','jpeg','png','gif'];
      $file_array = array_merge($image_array, ['doc','docx','xls','xlsx','pdf','txt']);
	  if($request->hasFile('file')&&$request->has('type')&&$request->has('folder')) {
	  	$type = $request->input('type');
	  	$folder = $request->input('folder');
	  	$file = $request->file('file');
	  	$file_size = $file->getClientSize();
	  	$file_name = (string) $file->getClientOriginalName();
	  	$file_ext = (string) $file->getClientOriginalExtension();
	  	if($file_size>$filesize*1000000){
	  		$error = $file_name.': El archivo debe tener un tamaño menor a '.$filesize.' MB.';
	  	} else if($type=='image'&&!in_array($file_ext, $image_array)){
	  		$error = $file_name.': Debe ingresar una imagen valida.';
	  	} else if($type=='file'&&!in_array($file_ext, $file_array)){
	  		$error = $file_name.': Debe ingresar un archivo en un formato valido.';
	  	}
	  } else {
	  	if(!$request->hasFile('file')){
	  		$error = 'Debe ingresar un archivo válido.';
	  	} else if(!$request->has('type')){
	  		$error = 'Debe ingresar un tipo de archivo.';
	  	} else if(!$request->has('folder')){
	  		$error = 'Debe ingresar un folder válido.';
	  	}
	  }
	  if($error===false){
	  	if($type=='image'){
	    	$new_file = Asset::upload_image($file, $folder);
	    	$get_file = Asset::get_image_path($folder, 'normal', $new_file);
	    	$get_thumb = Asset::get_image_path($folder, 'mini', $new_file);
	  	} else {
	    	$new_file = Asset::upload_file($file, $folder);
	    	$get_file = Asset::get_file($folder, $new_file);
	    	$get_thumb = $get_file;
	  	}
	  	\Solunes\Master\App\TempFile::create(['type'=>$type,'folder'=>$folder,'file'=>$new_file]);
	  	$response = ['files'=>[['name'=>$new_file,'url'=>$get_file,'thumbUrl'=>$get_thumb]]];
	  	$error_code = 200;
	  } else {
	  	$response = ['error'=>$error];
	  	$error_code = 400;
	  }
	  return response()->json($response)->setStatusCode($error_code);
    }

    public function postDelete(Request $request) {
      $error_code = 400;
	  if($request->has('file')&&$request->has('folder')&&$request->has('type')&&$request->has('action')) {
	  	$folder = $request->input('folder');
	  	$file = $request->input('file');
	  	$type = $request->input('type');
	  	if($request->input('action')=='saved'){
	  		\Solunes\Master\App\TempFile::create(['type'=>$type,'folder'=>$folder,'file'=>$file]);
	  	} else {
        	\Asset::delete_temp($type, $folder, $file);
	  	}
	  	$response = ['success'=>'Archivo eliminado'];
        $error_code = 200;
	  } else if($request->has('file')&&$request->has('folder')) {
	  	$response = ['error'=>'Debe ingresar un tipo de archivo válido'];
	  } else if($request->has('folder')) {
	  	$response = ['error'=>'Debe ingresar un archivo válido'];
	  } else {
	  	$response = ['error'=>'Debe ingresar un folder válido'];
	  }
	  return response()->json($response)->setStatusCode($error_code);
    }

}