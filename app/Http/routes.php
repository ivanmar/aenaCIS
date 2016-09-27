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

Route::any('/', array('as' => 'order', 'uses' => 'OrderController@index'));
Route::get('/order/{id}/destroy', 'OrderController@destroy');
Route::resource('order', 'OrderController');

Route::get('/js/getcustlist/{reqterm}', 'JsController@getCustList');
Route::post('/js/addcust', array('as'=>'js.addcust','uses' => 'JsController@addCust'));

Route::get('/customer/{id}/destroy', 'CustomerController@destroy');
Route::resource('customer', 'CustomerController');
