<?php

namespace Solunes\Master\App\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Validator;
use Auth;
use Login;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

    public function __construct(UrlGenerator $url) {
        $this->middleware('guest', ['except' => 'getLogout']);
	  	$this->prev = $url->previous();
    }

	public function getLogin(Request $request) {
		$check = Login::check($request->session());
      	return view('master::auth.login', ['failed_attempts'=>$check['failed_attempts'], 'blocked_time'=>$check['blocked_time']]);
	}

    public function postLogin(Request $request) {
    	$rules = \App\User::$rules_login;
    	// Añadir regla de comprobación Captcha si corresponde
    	if(config('solunes.nocaptcha_login')){
    		$rules['g-recaptcha-response'] = 'required|captcha';
    	}
	    $validator = Validator::make($request->all(), $rules);
	    $logged = false;
		if ($validator->passes()) {
			$last_session = session()->getId();
			if (Auth::attempt(array('email'=>$request->input('user'), 'password'=>$request->input('password')), true)) {
				$logged = true;
			} else if (Auth::attempt(array('username'=>$request->input('user'), 'password'=>$request->input('password')), true)) {
				$logged = true;
			} else if (Auth::attempt(array('cellphone'=>$request->input('user'), 'password'=>$request->input('password')), true)) {
				$logged = true;
			}
			if($logged){
			  if(Auth::user()->status=='banned'){
			  	Auth::logout();
			  	return Login::fail($request->session(), $validator, trans('master::form.login_banned'), 10, 5);
			  } else if(session()->has('url.intended')){
			  	return Login::success($request->session(), $last_session, Auth::user(), session()->get('url.intended'), trans('master::form.login_success'));
			  } else {
			    if(\Auth::user()->can('dashboard')){
			  		$redirect = 'admin';
			  	} else {
			  		$redirect = '';
			  	}
			  	return Login::success($request->session(), $last_session, Auth::user(), $redirect, trans('master::form.login_success'));
			  }
			} else {
			  	return Login::fail($request->session(), $validator, trans('master::form.login_fail'), 10, 5);
			}
		} else {
			return Login::fail($request->session(), $validator, trans('master::form.error_form'), 10, 5);
		}
    }

	public function getLogout(Request $request) {
		return Login::logout($request->session(), 'auth/login', trans('master::form.logout_success'));
	}

	public function getUnsuscribe($email) {
		if($user = \App\User::where('email',urldecode($email))->first()){
			$user->notifications_email = 0;
			$user->save();
			return redirect('auth/login')->with('message_success', 'Su correo fue retirado de nuestra lista de envío de correos. Muchas gracias por su tiempo.');
		} else {
			return redirect('auth/login')->with('message_error', 'No se pudo quitar la suscripción porque no se encontró un usuario que coincida con su correo electrónico.');
		}
	}

}