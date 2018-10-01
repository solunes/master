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
        return redirect('admin');
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
        $authUser = User::create([
            'first_name'     => $user->name,
            'last_name'     => $user->name,
            'password'     => '12345678',
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