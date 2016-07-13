<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controller('admin', 'AdminController');
Route::controller('auth', 'Auth\LoginController');
Route::controller('password', 'Auth\PasswordController');
Route::controller('account', 'Auth\AccountController');
Route::controller('asset', 'AssetController');