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
    if(config('solunes.master_dashboard')){
        Route::get('/', 'AdminController@getIndex');
    }
    Route::get('generate-manual/{role_name?}', 'AdminController@getGenerateManual');
    Route::get('my-notifications', 'AdminController@getMyNotifications');
    Route::post('read-notifications', 'AdminController@postReadNotifications');
    Route::get('my-inbox', 'AdminController@getMyInbox');
    Route::get('create-inbox', 'AdminController@getCreateInbox');
    Route::post('create-inbox', 'AdminController@postCreateInbox');
    Route::get('inbox/{id}', 'AdminController@getInboxId');
    Route::post('inbox-reply', 'AdminController@postInboxReply');
    // Indicadores
    Route::get('assign-indicator-modal', 'AdminController@getAssignIndicatorModal');
    Route::post('assign-indicators', 'AdminController@postAssignIndicators');
    Route::get('remove-indicator/{indicator_id}', 'AdminController@getRemoveIndicator');
    Route::get('model/indicator/{action}/{id?}/{lang?}', 'AdminController@getModelIndicator');
    //Route::get('indicators', 'AdminController@getIndicators');
    //Route::get('change-indicator-user/{type}/{action}/{id}', 'AdminController@changeIndicatorUser');
    // Modelos globales
    Route::get('model-list/{model}', 'AdminController@getModelList');
    Route::get('model/{model}/{action}/{id?}/{lang?}', 'AdminController@getModel');
    Route::get('child-model/{model}/{action}/{id?}/{lang?}', 'AdminController@getChildModel');
    Route::post('model', 'AdminController@postModel');
    // Filters
    Route::get('modal-filter/{category}/{type}/{category_id}/{node_name}', 'AdminController@getModalFilter');
    Route::post('modal-filter', 'AdminController@postModalFilter');
    Route::get('delete-filter/{id}', 'AdminController@getDeleteFilter');
    Route::get('delete-all-filters/{category}/{category_id}/{node_id?}', 'AdminController@getDeleteAllFilters');
    Route::get('delete-filter/{id}', 'AdminController@getDeleteFilter');
    Route::get('modal-map/{name}/{value}', 'AdminController@getModalMap');
    // Editar Lista
    Route::get('edit-list/{category}/{type}/{category_id}/{node_name}', 'AdminController@getModalEditList');
    Route::post('edit-list', 'AdminController@postModalEditList');
    // Códigos de Barras
    Route::get('redirect-barcode/{node_name}/{item_id}', 'AdminController@redirectBarcode');
    Route::get('check-barcode/{node_id}/{barcode}', 'AdminController@checkBarcode');
    Route::get('generate-barcode-image/{barcode}', 'AdminController@generateBarcodeImage');
    // Actualización de Contenido por AJAX
    Route::get('generate-item-field/{node_name}/{field_name}/{item_id}', 'AdminController@generateItemField');
    Route::post('item-field-update', 'AdminController@postItemFieldUpdate');
    // Formularios Dinámicos
    Route::get('import-nodes/{node_id?}', 'DynamicFormController@getImportNodes');
    Route::post('import-nodes', 'DynamicFormController@postImportNodes');
    Route::get('export-nodes', 'DynamicFormController@getExportNodes');
    Route::post('export-nodes', 'DynamicFormController@postExportNodes');
    Route::get('export-node/{node_name?}', 'DynamicFormController@getExportNode');
    Route::get('export-node-system/{node_name}', 'DynamicFormController@getExportNodeSystem');
    Route::get('form-list', 'DynamicFormController@getFormList');
    Route::get('form-fields/{id}', 'DynamicFormController@getFormFields');
    Route::get('form/{action}/{id?}', 'DynamicFormController@getForm');
    Route::post('form', 'DynamicFormController@postForm');
    Route::get('form-field/{action}/{parent_id}/{id?}', 'DynamicFormController@getFormField');
    Route::post('form-field', 'DynamicFormController@postFormField');
    Route::get('form-field-order/{parent_id}/{name}/{action}', 'DynamicFormController@getFormFieldOrder');
    Route::get('export-forms', 'DynamicFormController@getExportForms');
});

Route::group(['prefix'=>'customer-admin'], function(){
    if(config('solunes.master_dashboard')){
        Route::get('/', 'AdminController@getIndex');
    }
    // Modelos globales
    Route::get('model-list/{model}', 'CustomerAdminController@getModelList');
    Route::get('model/{model}/{action}/{id?}/{lang?}', 'CustomerAdminController@getModel');
    Route::get('child-model/{model}/{action}/{id?}/{lang?}', 'CustomerAdminController@getChildModel');
    Route::post('model', 'CustomerAdminController@postModel');
    // Inbox
    Route::get('my-inbox/{id?}', 'CustomerAdminController@getMyInbox');
    Route::get('conversation/{inbox_id}', 'CustomerAdminController@getInboxConversation');
    Route::get('create-inbox', 'CustomerAdminController@getCreateInbox');
    Route::post('create-inbox', 'CustomerAdminController@postCreateInbox');
    Route::get('inbox/{id}', 'CustomerAdminController@getInboxId');
    Route::post('inbox-reply', 'CustomerAdminController@postInboxReply');
    // Editar Lista
    Route::get('edit-list/{category}/{type}/{category_id}/{node_name}', 'CustomerAdminController@getModalEditList');
    Route::post('edit-list', 'CustomerAdminController@postModalEditList');
});
Route::group(['prefix'=>'auth'], function(){
    Route::get('login', 'Auth\LoginController@getLogin');
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::get('logout', 'Auth\LoginController@getLogout');
    Route::get('info', 'Auth\LoginController@getInfo');
    Route::get('send-confirmation-email/{encoded_email}', 'Auth\LoginController@getSendConfirmationEmail');
    Route::get('verify-email/{encrypted_email}', 'Auth\LoginController@getVerifyEmail');
    Route::get('unsuscribe/{email}', 'Auth\LoginController@getUnsuscribe');
});
Route::group(['prefix'=>'password'], function(){
    Route::get('recover', 'Auth\PasswordController@getRecover');
    Route::post('request', 'Auth\PasswordController@postRequest');
    Route::get('reset/{token?}', 'Auth\PasswordController@getReset');
    Route::post('update', 'Auth\PasswordController@postUpdate');
});
Route::group(['prefix'=>'asset'], function(){
    Route::post('froala-image-upload', 'AssetController@postFroalaImageUpload');
    Route::post('froala-file-upload', 'AssetController@postFroalaFileUpload');
    Route::post('upload', 'AssetController@postUpload');
    Route::post('delete', 'AssetController@postDelete');
});
Route::group(['prefix'=>'filter'], function(){
    Route::get('standard-filter/{parent_node_name}/{relation_name}/{parent_value}', 'FilterController@getStandardFilter');
});
Route::group(['prefix'=>'test'], function(){
    Route::get('general-test', 'TestController@getGeneralTest');
    Route::get('generate-help-edit-fields/{node}/{type}/{action}/{id}', 'TestController@generateHelpEditFields');
    Route::get('preview-email/{msg}', 'TestController@previewEmail');
    Route::get('test-template-2', 'TestController@previewTemplate2');
});
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');