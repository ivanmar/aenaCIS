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

Route::any('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));


Route::get('/js/getcontactlist/{reqterm}', 'JsController@getContactList');
Route::post('/js/addcontact', array('as'=>'js.addcontact','uses' => 'JsController@addContact'));
Route::post('/js/addcomment', array('as'=>'js.addcomment','uses' => 'JsController@addComment'));
Route::post('/js/addevent', array('as'=>'js.addevent','uses' => 'JsController@addEvent'));

Route::get('/company/{id}/destroy', 'CompanyController@destroy');
Route::resource('company', 'CompanyController');

Route::get('/contact/{id}/destroy', 'ContactController@destroy');
Route::resource('contact', 'ContactController');

Route::get('/ticket/{id}/destroy', 'TicketController@destroy');
Route::resource('ticket', 'TicketController');

Route::get('/project/{id}/destroy', 'ProjectController@destroy');
Route::resource('project', 'ProjectController');