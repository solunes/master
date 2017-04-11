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
    // Modelos globales
    Route::get('model-list/{model}', 'AdminController@getModelList');
    Route::get('model/{model}/{action}/{id?}/{lang?}', 'AdminController@getModel');
    Route::get('child-model/{model}/{action}/{id?}/{lang?}', 'AdminController@getChildModel');
    Route::post('model', 'AdminController@postModel');
    // Indicadores
    Route::get('model/indicator/{action}/{id?}/{lang?}', 'AdminController@getModelIndicator');
    Route::get('indicators', 'AdminController@getIndicators');
    Route::get('change-indicator-user/{type}/{action}/{id}', 'AdminController@changeIndicatorUser');
    // Filters
    Route::get('modal-filter/{category}/{type}/{category_id}/{node_name}', 'AdminController@getModalFilter');
    Route::post('modal-filter', 'AdminController@postModalFilter');
    Route::get('delete-filter/{id}', 'AdminController@getDeleteFilter');
    Route::get('delete-all-filters/{category}/{category_id}/{node_id?}', 'AdminController@getDeleteAllFilters');
    Route::get('modal-map/{name}/{value}', 'AdminController@getModalMap');
    // Códigos de Barras
    Route::get('redirect-barcode/{node_name}/{item_id}', 'AdminController@redirectBarcode');
    Route::get('check-barcode/{node_id}/{barcode}', 'AdminController@checkBarcode');
    Route::get('generate-barcode-image/{barcode}', 'AdminController@generateBarcodeImage');
    // Formularios Dinámicos
    Route::get('form-list', 'DynamicFormController@getFormList');
    Route::get('form-fields/{id}', 'DynamicFormController@getFormFields');
    Route::get('form/{action}/{id?}', 'DynamicFormController@getForm');
    Route::post('form', 'DynamicFormController@postForm');
    Route::get('form-field/{action}/{parent_id}/{id?}', 'DynamicFormController@getFormField');
    Route::post('form-field', 'DynamicFormController@postFormField');
    Route::get('form-field-order/{parent_id}/{name}/{action}', 'DynamicFormController@getFormFieldOrder');
    Route::get('export-forms', 'DynamicFormController@getExportForms');
    Route::get('import-forms', 'DynamicFormController@getImportForms');
});
Route::group(['prefix'=>'auth'], function(){
    Route::get('login', 'Auth\LoginController@getLogin');
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::get('logout', 'Auth\LoginController@getLogout');
});
Route::group(['prefix'=>'password'], function(){
    Route::get('recover', 'Auth\PasswordController@getRecover');
    Route::post('request', 'Auth\PasswordController@postRequest');
    Route::get('reset/{token?}', 'Auth\PasswordController@getReset');
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
Route::group(['prefix'=>'test'], function(){
    Route::get('general-test', 'TestController@getGeneralTest');
});