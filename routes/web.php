<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app');
});
Route::post('/sign/{type}', 'SignController@sign');
Route::get('/getLog', 'SignController@get_log');
Route::post('/register', 'UserController@register');
Route::post('/login/{number}', 'UserController@login');
Route::post('/auto_login/{id}', 'UserController@auto_login');

Route::get('/signMag','SignController@sign_mag');
Route::get('/getUser','UserController@get_user');
Route::post('/getSign', 'SignController@get_sign');
Route::post('/userSign/{id}', 'SignController@user_sign');

Route::get('/userMag','UserController@user_mag');
Route::post('/userDel/{id}','UserController@user_del');
Route::post('/userEdit/{id}/{type}','UserController@user_edit');
Route::get('/userMsg','UserController@user_msg');




