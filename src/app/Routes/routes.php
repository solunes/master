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

Route::group(['prefix'=>'admin'], function(){
    Route::get('/', 'AdminController@getIndex');
    Route::get('generate-manual/{role_name?}', 'AdminController@getGenerateManual');
    Route::get('model-list/{model}', 'AdminController@getModelList');
    Route::get('model/{model}/{action}/{id?}/{lang?}', 'AdminController@getModel');
    Route::post('model', 'AdminController@postModel');
});
Route::group(['prefix'=>'auth'], function(){
    Route::get('login', 'Auth\LoginController@getLogin');
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::get('logout', 'Auth\LoginController@getLogout');
});
Route::group(['prefix'=>'password'], function(){
    Route::get('recover', 'Auth\PasswordController@getRecover');
    Route::post('request', 'Auth\PasswordController@postRequest');
    Route::get('reset', 'Auth\PasswordController@getReset');
    Route::post('update', 'Auth\PasswordController@postUpdate');
});
Route::group(['prefix'=>'account'], function(){
    Route::get('/', 'Auth\AccountController@getIndex');
    Route::post('password', 'Auth\AccountController@postPassword');
});
Route::group(['prefix'=>'asset'], function(){
    Route::post('froala-image-upload', 'AssetController@postFroalaImageUpload');
    Route::post('froala-file-upload', 'AssetController@postFroalaFileUpload');
    Route::post('upload', 'AssetController@postUpload');
    Route::post('delete', 'AssetController@postDelete');
});