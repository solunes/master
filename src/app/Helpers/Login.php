<?php 

namespace Solunes\Master\App\Helpers;

use Mail;
use Auth;

class Login {

    public static function check($session) {
        if($session->has('login_fail')){
            $failed_attempts = $session->get('login_fail');
        } else {
            $failed_attempts = 0;
        }
        if($session->has('login_block')){
            $session_block = strtotime($session->get('login_block'));
            $date = time();
            $blocked_time=ceil(($session_block-$date)/60);
            if($blocked_time<1){
                $session->forget('login_block');
                $blocked_time = 0;
            }
        } else {
            $blocked_time = 0;
        }
        return array('blocked_time'=>$blocked_time, 'failed_attempts'=>$failed_attempts);
    }

    public static function fail($session, $validator, $message, $max_fails = 10, $blocked_time = 5) {
        if($session->has('login_fail')){
          if($session->get('login_fail')>=($max_fails-1)) {
            $session->put('login_block', date('Y-m-d H:i:s', time()+($blocked_time*60)));
            $session->put('login_fail', '0');
          } else {
            $session->put('login_fail', $session->get('login_fail')+1);
          }
        } else {
            $session->put('login_fail', '1');
        }
        return redirect('auth/login')->with('message_error', $message)->withErrors($validator)->withInput();
    }

    public static function success($session, $last_session, $user, $redirect, $message, $type = false) {
        $session->forget('login_fail');
        $user->timestamps = false;
        $user->last_session = $last_session;
        $user->save();
        if($type==false){
            return redirect()->intended($redirect)->with('message_success', $message);
        } else {
            if($session->has('url.intended')){
                $redirect .= '?intended_url='.urlencode($session->get('url.intended'));
            }
            return redirect($redirect)->with('message_success', $message);
        }
    }

    public static function logout($session, $redirect, $message) {
        Auth::logout();
        return redirect($redirect)->with('message_success', $message);
    }

    public static function failed_try($validator, $redirect, $message) {
        return redirect($redirect)->with('message_error', $message)->withErrors($validator)->withInput();
    }

    public static function pass_recover_success($email, $redirect, $message, $expire_time = 60) {
        $now = new \DateTime();
        $now->add(new \DateInterval('PT1H'));
        $token = md5($email.rand());
        if (\App\PasswordReminder::where('email', $email)->count()>0) {
            \App\PasswordReminder::where('email', $email)->update(array('token'=>$token, 'created_at'=>$now));
        } else {
            $password_reminder = new \App\PasswordReminder;
            $password_reminder->email = $email;
            $password_reminder->token = $token;
            $password_reminder->created_at = $now;
            $password_reminder->save();
        }
        Mail::send('master::emails.reminder', ['token' => $token], function($m) use($email) {
            $m->to($email, 'User')->subject(config()->get('app.name').' | '.trans('master::mail.remind_password_title'));
        });
        return redirect($redirect)->with('message_success', $message);
    }

    public static function get_role_permissions($role_name = NULL) {
        if($role_name||\Auth::check()){
            if($role_name){
              $role = \Solunes\Master\App\Role::where('name', $role_name)->first();
            } else if(\Auth::check()) {
              $role = \Auth::user()->role_user()->first();
            }
            $return = $role->permission_role()->lists('name')->toArray();
        } else {
            $return = [];
        }
        return $return;
    }

    public static function redirect_dashboard($error) {
        return redirect('admin')->with('message_error', trans('master::admin.'.$error));
    }

}