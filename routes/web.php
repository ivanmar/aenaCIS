<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', 'TicketController@index');


Route::get('/js/getcontactlist/{reqterm}', 'JsController@getContactList');
Route::post('/js/addcontact', array('as'=>'js.addcontact','uses' => 'JsController@addContact'));
Route::post('/js/addcomment', array('as'=>'js.addcomment','uses' => 'JsController@addComment'));
Route::post('/js/addtask', array('as'=>'js.addtask','uses' => 'JsController@addTask'));

Route::post('/js/addsessinvoout', array('as'=>'js.addsessinvoout','uses' => 'JsController@addSessInvoOut'));
Route::post('/js/addsessproductin', array('as'=>'js.addsessproductin','uses' => 'JsController@addSessProductIn'));
Route::post('/js/delsessinvoout', array('as'=>'js.delsessinvoout','uses' => 'JsController@delSessInvoOut'));
Route::post('/js/delsessproductin', array('as'=>'js.delsessproductin','uses' => 'JsController@delSessProductIn'));

Route::get('/company/{id}/destroy', 'CompanyController@destroy');
Route::resource('company', 'CompanyController');

Route::get('/contact/{id}/destroy', 'ContactController@destroy');
Route::resource('contact', 'ContactController');

Route::get('/ticket/{id}/destroy', 'TicketController@destroy');
Route::resource('ticket', 'TicketController');

Route::get('/project/{id}/destroy', 'ProjectController@destroy');
Route::resource('project', 'ProjectController');

Route::get('/kb/{id}/destroy', 'KbController@destroy');
Route::resource('kb', 'KbController');

Route::get('/product/{id}/destroy', 'ProductController@destroy');
Route::resource('product', 'ProductController');

Route::get('/productgroup/{id}/destroy', 'ProductGroupController@destroy');
Route::resource('productgroup', 'ProductGroupController');

Route::get('/manufacturer/{id}/destroy', 'ManufacturerController@destroy');
Route::resource('manufacturer', 'ManufacturerController');

Route::get('/service/{id}/destroy', 'ServiceController@destroy');
Route::resource('service', 'ServiceController');

Route::get('/invoiceout/{id}/destroy', 'InvoiceOutController@destroy');
Route::resource('invoiceout', 'InvoiceOutController');

Route::get('/invoicein/{id}/destroy', 'InvoiceInController@destroy');
Route::resource('invoicein', 'InvoiceInController');

Route::get('/saleorder/{id}/destroy', 'SaleOrderController@destroy');
Route::resource('saleorder', 'SaleOrderController');

Route::get('/reclamation/{id}/destroy', 'ReclamationController@destroy');
Route::resource('reclamation', 'ReclamationController');