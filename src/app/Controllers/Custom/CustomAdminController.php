<?php

namespace Solunes\Master\App\Controllers\Custom;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use Validator;
use Asset;
use AdminList;
use AdminItem;
use PDF;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomAdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	public function getIndex() {
		$array['activities'] = \App\Activity::with('node','user')->orderBy('created_at', 'DESC')->get()->take(20);
		$array['notifications'] = \Auth::user()->notifications->take(20);
      	return view('list.dashboard', $array);
	}

    public function getGenerateManual($role_name = NULL) {
	    $permission_array = \Login::get_role_permissions($role_name);
	    $array = ['role_name'=>$role_name];
	    $array['title'] = 'Manual de Administrador';
	    $array['site'] = \App\Site::find(1);
	    $array['nodes'] = \App\Node::whereNull('parent_id')->whereIn('permission', $permission_array)->with('fields', 'children.fields', 'children.children.fields')->get();
	    $pdf = \PDF::loadView('pdf.manual', $array);
	    $header = \View::make('pdf.header', $array);
	    return $pdf->setPaper('letter')->setOption('header-html', $header->render())->stream(trans('admin.manual').'_'.date('Y-m-d').'.pdf');
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

	public function getTargetListList($id = NULL) {
      return AdminList::get_list($this, $id, 'target-list', ['filter'=>['date']]);
	}

	public function getTargetEmail($action, $id) {
	  $city_options = \App\City::lists('name', 'id');
	  $status_options = ['active'=>'Activo', 'inactive'=>'Inactivo'];
	  $product_options = \App\Product::where('status', 'active')->whereIn('type', ['important_product', 'product'])->lists('name', 'id');
      return AdminItem::get_request('target-list', $action, $id, $this, [], ['city_options'=>$city_options,'type_options'=>$type_options, 'product_options'=>$product_options]);
	}

    public function postTargetEmail(Request $request) {
      $action = $request->input('action');
      $response = AdminItem::post_request('target-email', $action, $request);
      $item = $response[1];
	  if($response[0]->passes()) {
		if($action=='create'){
		  $item->customer_id = $request->input('customer_id');
		}
		$item->city_id = $request->input('city_id');
		$item->status = $request->input('status');
		$item->name = $request->input('name');
		$item->type = $request->input('type');
		$item->assigned_staff = $request->input('assigned_staff');
		$item->contract_signed = $request->input('contract_signed');
		$item->contract_duration = $request->input('contract_duration');
		$item->address = $request->input('address');
		$item->phone = $request->input('phone');
		$item->observations = $request->input('observations');
		$item->save();

		return AdminItem::post_success($action, $this->module.'/target-list/edit/'.$item->id);
	  } else {
		return AdminItem::post_fail($action, $this->prev, $response[0]);
	  }
    }

	public function getCustomerPointList($id) {
      return AdminList::get_list($this, $id, 'customer-point', ['parent'=>'customer']);
	}

	public function getCustomerPoint($action, $id) {
	  $city_options = \App\City::lists('name', 'id');
	  $days_options = \App\Day::lists('name', 'id');
	  $status_options = ['active'=>'Activo', 'inactive'=>'Inactivo'];
	  $type_options = ['agency'=>'Agencia', 'branch'=>'Sucursal', 'atm'=>'Cajero'];
	  $product_options = \App\Product::where('status', 'active')->whereIn('type', ['important_product', 'product'])->lists('name', 'id');
      return AdminItem::get_request('customer-point', $action, $id, $this, ['parent'=>'parent'], ['city_options'=>$city_options, 'days_options'=>$days_options, 'status_options'=>$status_options, 'type_options'=>$type_options, 'product_options'=>$product_options]);
	}

    public function postCustomerPoint(Request $request) {
      $action = $request->input('action');
      $response = AdminItem::post_request('customer-point', $action, $request);
      $item = $response[1];
	  if($response[0]->passes()) {
		if($action=='create'){
		  $item->customer_id = $request->input('customer_id');
		}
		$item->city_id = $request->input('city_id');
		$item->status = $request->input('status');
		$item->name = $request->input('name');
		$item->type = $request->input('type');
		$item->assigned_staff = $request->input('assigned_staff');
		$item->contract_signed = $request->input('contract_signed');
		$item->contract_duration = $request->input('contract_duration');
		$item->address = $request->input('address');
		$item->phone = $request->input('phone');
		$item->observations = $request->input('observations');
		$item->save();

		AdminItem::post_subitems('\App\PointFloor', 'floor', 'point_id', $item->id, ['name']);
		AdminItem::post_subitems('\App\PointSchedule', 'schedule', 'point_id', $item->id, ['status', 'day_id', 'initial_time', 'end_time', 'observations']);
		AdminItem::post_subitems('\App\PointProduct', 'product', 'point_id', $item->id, ['product_id', 'required_quantity']);
		return AdminItem::post_success($action, $this->module.'/customer-point/edit/'.$item->id);
	  } else {
		return AdminItem::post_fail($action, $this->prev, $response[0]);
	  }
    }

}