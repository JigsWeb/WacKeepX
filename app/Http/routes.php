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
Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('auth/logout', 'Auth\AuthController@logout');
Route::get('auth', 'Auth\AuthController@get');
Route::post('auth', 'Auth\AuthController@post');

Route::post("note", 'NoteController@create')->middleware(['auth']);
Route::put("note/{note}", 'NoteController@update')->middleware(['auth','note']);
Route::delete("note/{note}", 'NoteController@destroy')->middleware(['auth','note']);


/*Route::controllers([
    'password' => 'Auth\PasswordController',
]);*/
