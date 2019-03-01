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

Route::get('/', 'DashboardController@home');

// Authentication
Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@postLogin')->name('login');
Route::get('/logout', 'LoginController@logout')->name('logout');

Route::get('attendance/sample', 'AttendanceController@sample');
Route::resource('attendance', 'AttendanceController');


// App Routes
Route::group(['middleware' => ['auth'], 'prefix' => 'app', 'as' => 'app.'], function () {

    // Dashboard
    Route::get('homedashboard', 'DashboardController@dashboard')->name('dashboard')->middleware('access_control:administrator,user,encoder');

    // Dashboard of Attendance
    Route::get('attendancedashboard', 'DashboardController@attendanceDashboard')->name('attendanceDashboard')->middleware('access_control:administrator,user');

    // Users
    Route::resource('users', 'UserController')->middleware('access_control:administrator');

    // Teams
    Route::resource('teams', 'TeamsController')->middleware('access_control:administrator');

    // Clusters
    Route::resource('clusters', 'ClustersController')->middleware('access_control:administrator');

    // Applications
    Route::resource('applications', 'ApplicationController')->middleware('access_control:administrator,user,encoder');

    // Devices
    Route::resource('devices', 'DevicesController')->middleware('access_control:administrator');

    // Plan
    Route::resource('plans', 'PlansController')->middleware('access_control:administrator');

    // Message Board
    Route::resource('messages', 'MessageBoardController')->middleware('access_control:administrator,user,encoder');
    // Route::get('message-board', 'MessageBoardController@messageBoard')->name('messageboard');
    // delete specific post
    Route::post('delete-post', 'MessageBoardController@delete')->name('delete-post');


    // For non-admin
    Route::get('your-clusters', 'NonAdminController@yourClusters')->name('your.clusters');
    Route::get('your-cluster/{id}', 'NonAdminController@clusterShow')->name('your.clusters.show');
    Route::get('your-teams/{id}', 'NonAdminController@teamShow')->name('your.teams.show');

});

    // Route::resource('messages', 'MessageBoardController');
