<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'LoginController@login');

Route::middleware('auth:api')->group(function()
{
    Route::get('users', 'UserController@index');
    Route::post('user', 'UserController@store');
    Route::get('users/{user}', 'UserController@show');
    Route::patch('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy');

    Route::post('logout', 'LoginController@logout');
});
