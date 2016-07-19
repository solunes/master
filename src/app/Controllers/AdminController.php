<?php

namespace Solunes\Master\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use Validator;
use Asset;
use AdminList;
use AdminItem;
use PDF;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	public function getIndex() {
		$array['activities'] = \Solunes\Master\App\Activity::with('node','user')->orderBy('created_at', 'DESC')->get()->take(20);
		$array['notifications'] = \Auth::user()->notifications->take(20);
      	return view('master::list.dashboard', $array);
	}

    public function getGenerateManual($role_name = NULL) {
	    $permission_array = \Login::get_role_permissions($role_name);
	    $array = ['role_name'=>$role_name];
	    $array['title'] = 'Manual de Administrador';
	    $array['site'] = \Solunes\Master\App\Site::find(1);
	    $array['nodes'] = \Solunes\Master\App\Node::whereNull('parent_id')->whereIn('permission', $permission_array)->with('fields', 'children.fields', 'children.children.fields')->get();
	    $pdf = \PDF::loadView('master::pdf.manual', $array);
	    $header = \View::make('pdf.header', $array);
	    return $pdf->setPaper('letter')->setOption('header-html', $header->render())->stream(trans('master::admin.manual').'_'.date('Y-m-d').'.pdf');
	}

	public function getModelList($model) {
      return AdminList::get_list($this, $model);
	}

	public function getModel($model, $action, $id = NULL) {
      return AdminItem::get_request($model, $action, $id, $this, []);
	}

    public function postModel(Request $request) {
      $model = $request->input('model_node');
      $action = $request->input('action');
      $lang_code = $request->input('lang_code');
      $response = AdminItem::post_request($model, $action, $request);
      $item = $response[1];
	  if($response[0]->passes()) {
	  	$item = AdminItem::post_request_success($request, $model, $item, 'admin');
        return AdminItem::post_success($action, $this->module.'/model/'.$model.'/edit/'.$item->id);
	  } else {
		return AdminItem::post_fail($action, $this->prev, $response[0]);
	  }
    }

}