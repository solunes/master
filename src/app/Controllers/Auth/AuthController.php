<?php

namespace Solunes\Master\App\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Auth;
use Socialite;
use Validator;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AuthController extends Controller {

    public function __construct(UrlGenerator $url) {
        //$this->middleware('guest', ['except' => 'getLogout']);
        $this->prev = $url->previous();
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        if($authUser->status=='banned'||$authUser->status=='pending_confirmation'){
            $message = trans('master::form.login_'.$authUser->status);
            Auth::logout();
            if($authUser->status=='pending_confirmation'){
                $confirmation_url = url('auth/send-confirmation-email/'.urlencode($authUser->email));
                session()->set('confirmation_url', $confirmation_url);
            }
            return redirect($this->prev)->with('message_error', 'No puede iniciar sesión porque su cuenta no se encuentra disponible.');
        } else if(session()->has('url.intended')){
            return redirect(session()->get('url.intended'))->with('message_success', 'Felicidades, inició sesión correctamente.');
        } else {
            if(\Auth::user()->can('dashboard')){
                $redirect = 'admin';
            } else {
                $redirect = '';
            }
            return redirect($redirect);
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        $authUser = User::where('email', $user->email)->first();
        if ($authUser) {
        	$authUser->provider = $provider;
        	$authUser->provider_id = $user->id;
        	$authUser->save();
            return $authUser;
        }
        if(config('solunes.customer')){
            $authCustomer = \Solunes\Customer\App\Customer::where('email', $user->email)->first();
            if(!$authCustomer){
                $name = \External::reduceName($user->name);
                $first_name = $name['first_name'];
                $last_name = $name['last_name'];
                $authCustomer = \Solunes\Customer\App\Customer::create([
                    'first_name'     => $first_name,
                    'last_name'     => $last_name,
                    'password'     => '12345678',
                    'name'     => $name,
                    'email'    => $user->email,
                ]);
            }
            $authUser = $authCustomer->user;
            if ($authUser) {
                $authUser->provider = $provider;
                $authUser->provider_id = $user->id;
                $authUser->save();
                return $authUser;
            }
        } else {
            $name = \External::reduceName($user->name);
            $first_name = $name['first_name'];
            $last_name = $name['last_name'];
            $authUser = User::create([
                'first_name'     => $first_name,
                'last_name'     => $last_name,
                'password'     => '12345678',
                'name'     => $name,
                'email'    => $user->email,
                'provider' => $provider,
                'provider_id' => $user->id
            ]);
            $role = \Solunes\Master\App\Role::where('name','member')->first();
            $authUser->role_user()->attach($role->id);
        }
        return $authUser;
    }

}