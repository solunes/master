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

    public static function fail($session, $redirect, $validator, $message, $max_fails = 10, $blocked_time = 5) {
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
        return redirect($redirect)->with('message_error', $message)->withErrors($validator)->withInput();
    }

    public static function success($session, $last_session, $user, $redirect, $message, $type = false) {
        $session->forget('login_fail');
        if(config('solunes.store')){
            \CustomStore::after_login($user, $last_session, $redirect);
        } else if(config('solunes.sales')){
            \CustomSales::after_login($user, $last_session, $redirect);
        }
        if(config('solunes.after_login')){
            \CustomFunc::after_login($user, $last_session, $redirect);
        }
        if(session()->has('message_success')){
            $message = session()->get('message_success');
        }
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
        Mail::send('master::emails.reminder', ['token'=>$token, 'email'=>$email], function($m) use($email) {
            $m->to($email, 'User')->subject(config('solunes.app_name').' | '.trans('master::mail.remind_password_title'));
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

    public static function send_confirmation_email($email, $name) {
        if(session()->has('confirmation_url')){
            session()->forget('confirmation_url');
        }
        $confirmation_url = url('auth/verify-email/'.\Crypt::encrypt($email));
        $vars = ['name'=>$name, 'email'=>$email, 'confirmation_url'=>$confirmation_url];
        Mail::send('master::emails.verification-email', $vars, function($m) use($name, $email) {
            $m->to($email, $name)->subject(config('solunes.app_name').' | '.trans('master::mail.verify_email_title'));
        });
    }

    public static function find_or_create_customer($api_email, $api_name, $agency = NULL) {
        $authCustomer = NULL;
        if(config('solunes.customer')){
            if(config('customer.different_customers_by_agency')&&$agency){
                $authCustomer = \Solunes\Customer\App\Customer::where('email', $api_email)->where('agency_id', $agency->id)->first();
            } else if(!config('customer.different_customers_by_agency')) {
                $authCustomer = \Solunes\Customer\App\Customer::where('email', $api_email)->first();
            } else {
                return NULL;
            }
            if(!$authCustomer){
                if(config('customer.different_customers_by_agency')&&$agency){
                    $authUser = \App\User::where('email', $api_email)->where('agency_id', $agency->id)->first();
                } else if(!config('customer.different_customers_by_agency')) {
                    $authUser = \App\User::where('email', $api_email)->first();
                } 
                if($authUser){
                    $status = 'normal';
                    $user_id = $authUser->id;
                } else {
                    $status = 'ask_password';
                    $user_id = NULL;
                }
                $name = \External::reduceName($api_name);
                $first_name = $name['first_name'];
                $last_name = $name['last_name'];
                $authCustomer = \Solunes\Customer\App\Customer::create([
                    'first_name'     => $first_name,
                    'last_name'     => $last_name,
                    'password'     => config('customer.default_password'),
                    'name'     => $api_name,
                    'email'    => $api_email,
                    'status'    => $status,
                    'user_id' => $user_id
                ]);
                if(config('customer.different_customers_by_agency')&&$authUser->agency_id){
                    $authCustomer->agency_id = $authUser->agency_id;
                }
            }
        }
        return $authCustomer;
    }

}