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

Route::group(['prefix'=>'account'], function(){
    Route::get('/', 'Auth\AccountController@getIndex');
    Route::post('password', 'Auth\AccountController@postPassword');
});
Route::group(['prefix'=>'dashboard'], function(){
    Route::get('custom-form/{single_model}/{action}/{custom_type?}/{id?}', 'ProcessController@getModel');
    Route::post('model', 'ProcessController@postModel');
});