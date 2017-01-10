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

Route::get('/', 'DashboardController@index');


Route::get('/js/getcontactlist/{reqterm}', 'JsController@getContactList');
Route::post('/js/addcontact', array('as'=>'js.addcontact','uses' => 'JsController@addContact'));
Route::post('/js/addcomment', array('as'=>'js.addcomment','uses' => 'JsController@addComment'));
Route::post('/js/addtask', array('as'=>'js.addtask','uses' => 'JsController@addTask'));

Route::post('/js/addsessproduct', array('as'=>'js.addsessproduct','uses' => 'JsController@addSessProduct'));
Route::post('/js/addsessservice', array('as'=>'js.addsessservice','uses' => 'JsController@addSessService'));
Route::post('/js/delsessproduct', array('as'=>'js.delsessproduct','uses' => 'JsController@delSessProduct'));
Route::post('/js/delsessservice', array('as'=>'js.delsessservice','uses' => 'JsController@delSessService'));

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

Route::get('/service/{id}/destroy', 'ServiceController@destroy');
Route::resource('service', 'ServiceController');

Route::get('/invoiceout/{id}/destroy', 'InvoiceOutController@destroy');
Route::resource('invoiceout', 'InvoiceOutController');

Route::get('/invoicein/{id}/destroy', 'InvoiceInController@destroy');
Route::resource('invoicein', 'InvoiceInController');