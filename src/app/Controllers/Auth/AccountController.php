<?php

namespace Solunes\Master\App\Controllers\Auth;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccountController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

    public function getIndex() {
      return view('master::auth.pass-edit');
    }

    public function postPassword(Request $request) {
	  $error_messages = array(
		'password.confirmed' => trans('form.password_match_error'),
  	  );
	  $validator = Validator::make($request->all(), User::$rules_edit_pass, $error_messages);
	  if ($validator->passes()) {
		$user = Auth::user();
		$user->password = $request->input('password');
		$user->status = 'Normal';
		$user->save();
		if($request->has('intended_url')){
			$redirect = urldecode($request->input('intended_url'));
		} else if(!\Auth::user()->can('dashboard')) {
			$redirect = '';
		} else {
			$redirect = 'admin';
		}
		return redirect($redirect)->with('message_success', trans('form.password_edited'));
	  } else {
		return redirect('account')->with(array('message_error' => trans('form.error_form')))->withErrors($validator)->withInput();
	  }
    }
	
}