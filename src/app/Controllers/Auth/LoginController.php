<?php

namespace Solunes\Master\App\Controllers\Auth;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Login;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

	public function getLogin(Request $request) {
		$check = Login::check($request->session());
      	return view('master::auth.login', ['failed_attempts'=>$check['failed_attempts'], 'blocked_time'=>$check['blocked_time']]);
	}

    public function postLogin(Request $request) {
	    $validator = Validator::make($request->all(), \App\User::$rules_login);
		if ($validator->passes()) {
			if (Auth::attempt(array('email'=>$request->input('email'), 'password'=>$request->input('password')), true)) {
			  if(Auth::user()->status=='banned'){
			  	Auth::logout();
			  	return Login::fail($request->session(), $validator, trans('form.login_banned'), 10, 5);
			  } else if(Auth::user()->status=='ask_password'){
			  	return Login::success($request->session(), 'account', trans('form.login_success_password'), true);
			  } else {
			  	if(\Auth::user()->can('dashboard')){
			  		$redirect = 'admin';
			  	} else {
			  		$redirect = '';
			  	}
			  	return Login::success($request->session(), $redirect, trans('form.login_success'));
			  }
			} else {
			  	return Login::fail($request->session(), $validator, trans('form.login_fail'), 10, 5);
			}
		} else {
			return Login::fail($request->session(), $validator, trans('form.error_form'), 10, 5);
		}
    }

	public function getLogout(Request $request) {
		return Login::logout($request->session(), 'auth/login', trans('form.logout_success'));
	}

}