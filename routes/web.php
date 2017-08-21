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

/** 
 * Activity routes all start with a/{activity}. In all of these routes, Route
 * model binding is used to test the Authed user's authentication in the activity
 * i.e. that they have access, whether they are staff or student.
 * So every request after launch causes DB query to Auth::user()->activities,
 * which gets the activity with pivot table containing role and other data.
 */
Route::prefix('a/{activity}')->group(function() {
    /**
     * Staff routes, middleware prevents these being accessed unless user is
     * authed, has a relationship to the {activity}, and has role 'staff'
     */
    Route::group(['middleware' => ['staff']], function() {
        // staff activity routes
        Route::get('/', 'ActivityController@show');        // temporarily changed from show for dev
        Route::put('/', 'ActivityController@create');
        Route::put('close', 'ActivityController@close');
        Route::put('open', 'ActivityController@open');
        Route::get('setup', 'ActivityController@showSetup');

        Route::get('users/add', 'UserController@create');
        Route::post('users', 'UserController@store');
        Route::put('users/{userId}/group', 'UserController@updateGroup');

        // Group routes
        Route::get('groups', 'GroupController@index');
        Route::delete('groups/{groupId}', 'GroupController@delete');
        Route::post('groups/bulk', 'GroupController@bulk');
        Route::post('groups/batch', 'GroupController@batch');
        Route::put('groups/{groupId}', 'GroupController@update');
        Route::post('groups/{groupId}/users', 'GroupController@addUsers');

        // Round routes
        Route::post('rounds', 'RoundController@store');
        Route::delete('rounds/{roundId}', 'RoundController@delete');
        Route::put('rounds/{roundId}', 'RoundController@update');

        // show form for rating a student
        Route::get('r/{round}/rate/{studentId}', 'RatingController@show');
        Route::post('r/{round}/rate/{studentId}', 'RatingController@store');
    });

    /**
     * Student routes, middleware prevents these being accessed unless used is
     * authed, has a relationship to the {activity}, and role 'student'
     */
    Route::group(['middleware' => ['student', 'activity.open']], function() {
        // student linear routes
        Route::get('linear', 'LinearController@dashboard');
        Route::post('linear/r/{round}/p/{page}', 'LinearController@page');

        // student nonlinear routes
        Route::get('nonlinear', 'NonLinearController@dashboard');
        Route::post('nonlinear/r/{round}/c/{category}', 'NonLinearController@page');

        // student chart routes
        Route::get('student/r/{round}/chart/{scope?}', 'StudentChartController@show');
    });

    Route::get('closed', 'ActivityController@showClosed');
});

// security routes
Route::get('eject', 'ActivityController@eject');