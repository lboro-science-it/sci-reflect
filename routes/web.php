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

// lti launch route
Route::post('launch', 'LtiController@launch');

Route::group(['middleware' => ['staff']], function() {
    // staff activity routes
    Route::get('a/{activity}', 'ActivityController@show');
    Route::put('a/{activity}', 'ActivityController@create');
    Route::put('a/{activity}/close', 'ActivityController@close');
    Route::put('a/{activity}/open', 'ActivityController@open');

    Route::get('a/{activity}/add-users', 'UserController@create');
    Route::post('a/{activity}/add-users', 'UserController@store');

    // Group routes
    Route::get('a/{activity}/groups', 'GroupController@index');
    Route::delete('a/{activity}/groups/{groupId}', 'GroupController@delete');
    Route::post('a/{activity}/groups/bulk', 'GroupController@bulk');
    Route::post('a/{activity}/groups/batch', 'GroupController@batch');
    Route::put('a/{activity}/groups/{groupId}', 'GroupController@update');

    Route::get('a/{activity}/setup', 'ActivityController@showSetup');
});

Route::group(['middleware' => ['student']], function() {
    // student linear routes
    Route::get('a/{activity}/linear', 'LinearController@dashboard');
    Route::post('a/{activity}/linear/r/{round}/p/{roundPage}', 'LinearController@page');

    // student nonlinear routes
    Route::get('a/{activity}/nonlinear', 'NonLinearController@dashboard');
    Route::post('a/{activity}/nonlinear/r/{round}/c/{category}/p/{categoryPage}', 'NonLinearController@page');

    // student chart routes
    Route::get('a/{activity}/student/r/{round}/chart/{scope?}', 'StudentChartController@show');
});

// security routes
Route::get('eject', 'ActivityController@eject');