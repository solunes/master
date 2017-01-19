<?php

namespace Solunes\Master\App\Controllers\Auth;

use Illuminate\Http\Request;
use Validator;
use Mail;
use Login;

use App\Http\Controllers\Controller;

class PasswordController extends Controller {

    public function __construct() {
      $this->middleware('guest');
    }

    public function getRecover() {
      return view('master::auth.pass-recover');
    }
    
    public function postRequest(Request $request) {
      $error_messages = array('email.exists' => trans('master::form.email_exists_error'));
      $validator = Validator::make($request->all(), \App\PasswordReminder::$rules_reminder, $error_messages);
      if ($validator->passes()) {
        $email = $request->input('email');
        return Login::pass_recover_success($email, 'auth/login', trans('master::form.password_request_success'), 60);
      } else {
        return Login::failed_try($validator, 'password/recover', trans('master::form.password_request_error'));
      }
    }
    
    public function getReset($token = null) {
      if (is_null($token)) return redirect('password/forget')->with('message_error', trans('master::form.password_reset_error'));
      if (\App\PasswordReminder::where('token', $token)->count()>0) {
        return view('master::auth.pass-reset', ['token'=>$token]);
      } else {
        return Login::failed_try(NULL, 'password/recover', trans('master::form.password_reset_error'));
      }
    }
    
    public function postUpdate(Request $request) {
      $error_messages = array('reminder_password.confirmed' => trans('master::form.password_match_error'));
      $token = $request->input('token');
      $validator = Validator::make($request->all(), \App\User::$rules_edit_pass, $error_messages);
      if ($validator->passes()) {
        $now = new \DateTime();
        if ((\App\PasswordReminder::where('token', $token)->count()>0)&&(\App\PasswordReminder::where('token', $token)->first()->created_at<$now)) {
          $email = \App\PasswordReminder::where('token', $token)->first()->email;
          \App\User::where('email', $email)->update(array('password' => bcrypt($request->input('password'))));
          \App\PasswordReminder::where('token', $token)->delete();
          return redirect('auth/login')->with('message_success', trans('master::form.password_reset_success'));        
        } else {
          return Login::failed_try($validator, 'password/recover', trans('master::form.password_reset_error'));
        }
      } else {
          return redirect('password/reset/'.$token)->with('message_error', trans('master::form.password_not_edited'))->withErrors($validator)->withInput();
      }
    }

}