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
    public function redirectToProvider($provider, $agency_token = NULL)
    {
        if($agency_token){
            return Socialite::driver($provider)->stateless()->with(['agency_token' => $agency_token])->redirect();
        } else {
            return Socialite::driver($provider)->stateless()->redirect();
        }
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
        $user = Socialite::driver($provider)->stateless()->user();
        $agency_token = request()->input('agency_token');
        $agency = NULL;
        if(config('customer.different_customers_by_agency')&&$agency_token){
            $agency = \Business::getAgencyByToken($agency_token);
        }
        $authUser = $this->findOrCreateUser($user, $provider, $agency);
        $last_session = session()->getId();
        Auth::login($authUser, true);
        if(config('solunes.customer')&&config('customer.tracking')){
            if($customer = $authUser->customer){
                \Customer::createCustomerActivity($customer, 'login', 'El usuario inició sesión correctamente.');
            }
        }
        /*if($authUser->status=='ask_password'){
            if(config('solunes.sales')){
                \CustomSales::after_login($authUser, $last_session, config('customer.after_login_no_password'));
            }
            if(config('solunes.after_login')){
                \CustomFunc::after_login($authUser, $last_session, config('customer.after_login_no_password'));
            }
            return redirect(config('customer.after_login_no_password'))->with('message_success', 'Inició sesión correctamente, sin embargo le recomendamos cambiar su contraseña.');
        } else */
        if($authUser->status=='banned'||$authUser->status=='pending_confirmation'){
            $message = trans('master::form.login_'.$authUser->status);
            Auth::logout();
            if($authUser->status=='pending_confirmation'){
                $confirmation_url = url('auth/send-confirmation-email/'.urlencode($authUser->email));
                session()->set('confirmation_url', $confirmation_url);
            }
            return \Login::fail(request()->session(), $this->prev, [], $message, 10, 5);
        } else if(session()->has('url.intended')){
            $redirect = session()->get('url.intended');
            if(config('solunes.sales')){
                \CustomSales::after_login($authUser, $last_session, $redirect);
            }
            if(config('solunes.after_login')){
                \CustomFunc::after_login($authUser, $last_session, $redirect);
            }
            return redirect($redirect)->with('message_success', trans('master::form.login_success'));
        } else {
            if(\Auth::user()->can('dashboard')){
                $redirect = 'admin';
            } else if(config('customer.redirect_after_login')) {
                $redirect = config('customer.redirect_after_login');
            } else {
                $redirect = '';
            }
            if(config('solunes.sales')){
                \CustomSales::after_login($authUser, $last_session, $redirect);
            }
            if(config('solunes.after_login')){
                \CustomFunc::after_login($authUser, $last_session, $redirect);
            }
            return redirect($redirect)->with('message_success', trans('master::form.login_success'));
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider, $agency = NULL)
    {
        $avatar = NULL;
        if(config('customer.fields.image')){
            $avatar = $user->getAvatar();
        }
        $authCustomer = \Login::find_or_create_customer($user->email, $user->name, $avatar, $agency);
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
        $name = \External::reduceName($user->name);
        $first_name = $name['first_name'];
        $last_name = $name['last_name'];
        $authUser = User::create([
            'first_name'     => $first_name,
            'last_name'     => $last_name,
            'password'     => config('customer.default_password'),
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
        $role = \Solunes\Master\App\Role::where('name','member')->first();
        $authUser->role_user()->attach($role->id);
        return $authUser;
    }

}