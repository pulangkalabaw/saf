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
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    // Users
    Route::resource('users', 'UserController')->middleware('admin_only');
    // Teams
    Route::resource('teams', 'TeamsController')->middleware('admin_only');
    // Clusters
    Route::resource('clusters', 'ClustersController')->middleware('admin_only');
    // Applications
    Route::resource('applications', 'ApplicationController');
    // Statuses
    Route::resource('statuses', 'StatusesController')->middleware('admin_only');
    // Product
    Route::resource('product', 'ProductController')->middleware('admin_only');
    // Devices
    Route::resource('devices', 'DevicesController')->middleware('admin_only');
    // Plan
    Route::resource('plans', 'PlansController')->middleware('admin_only');
    // Message Board
    Route::resource('messages', 'MessageBoardController');
    // Route::get('message-board', 'MessageBoardController@messageBoard')->name('messageboard');

    // For non-admin
    Route::get('your-clusters', 'NonAdminController@yourClusters')->name('your.clusters');
    Route::get('your-cluster/{id}', 'NonAdminController@clusterShow')->name('your.clusters.show');
    Route::get('your-teams/{id}', 'NonAdminController@teamShow')->name('your.teams.show');

});

    // Route::resource('messages', 'MessageBoardController');
