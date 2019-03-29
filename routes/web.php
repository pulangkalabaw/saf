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

Route::get('/login', 'DashboardController@home');

// Authentication
Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@postLogin')->name('login');
Route::get('/logout', 'LoginController@logout')->name('logout');

//FORGOT PASSWORD
Route::get('/forgot-password', 'changePasswordController@forgotPassword')->name('forgot');
Route::post('/password-reset', 'changePasswordController@passwordReset')->name('password_reset');
//RESET PASSWORD LINK TO EMAIL
Route::get('/reset-password-link', 'changePasswordController@reset')->name('reset-password-link');
//RESET PASSWORD && SET NEW PASSWORD
Route::post('/get-new-password/{token}', 'changePasswordController@getnewPassword')->name('get-new-password');
Route::get('/get-new-password/{token}', 'changePasswordController@getnewPassword')->name('get-new-password');
Route::post('/set-new-password/{token}', 'changePasswordController@setnewPassword')->name('set-new-password');




// App Routes
Route::group(['middleware' => ['auth'], 'prefix' => 'app', 'as' => 'app.'], function () {

    // Dashboard
    Route::get('homedashboard', 'DashboardController@dashboard')->name('dashboard')->middleware('access_control:administrator,user,encoder');
    Route::get('encoderdashboard', 'DashboardController@encoderDashboard')->name('encoder-dashboard')->middleware('access_control:administrator,user,encoder');

    // Dashboard of Attendance
    Route::get('attendancedashboard', 'DashboardController@attendanceDashboard')->name('attendanceDashboard')->middleware('access_control:administrator,user');

    // Users
    Route::post('users/import-users', 'UserController@importUsers')->name('import-users');
    Route::resource('users', 'UserController')->middleware('access_control:administrator');

    // Teams
    Route::resource('teams', 'TeamsController')->middleware('access_control:administrator');

    // Clusters
    Route::resource('clusters', 'ClustersController')->middleware('access_control:administrator');

    // Applications
    Route::resource('applications', 'ApplicationController')->middleware('access_control:administrator,user,encoder');

    // Plan
    Route::resource('plans', 'PlansController')->middleware('access_control:administrator');

    // Message Board
    Route::resource('messages', 'MessageBoardController')->middleware('access_control:administrator,user,encoder');
    Route::get('message-board', 'MessageBoardController@messageBoard')->name('messageboard');
    // delete specific post
    Route::post('delete-post', 'MessageBoardController@delete')->name('delete-post');

    // Attendance
    Route::get('attendance/list', 'AttendanceController@list')->name('attendance.list');
    Route::get('attendance/sample', 'AttendanceController@sample');
    Route::resource('attendance', 'AttendanceController');
    Route::get('change-password', 'changePasswordController@changePassword');
    Route::post('handle-changePassword', 'changePasswordController@handleChangePassword')->name('changePassword');
    // GALLERY
    Route::get('gallery', 'AttendanceController@gallery')->name('gallery');
    Route::delete('image/{id}', 'AttendanceController@destroy_image')->name('image.destroy');
    Route::get('gallery/get/json', 'AttendanceController@getGallery')->name('gallery.json');


    // OIC
    // Route::resource('oic','OicController')->middleware('access_control:administrator,user,encoder');

    // For non-admin
    Route::get('your-clusters', 'NonAdminController@yourClusters')->name('your.clusters');
    Route::get('your-cluster/{id}', 'NonAdminController@clusterShow')->name('your.clusters.show');
    Route::get('your-teams/{id}', 'NonAdminController@teamShow')->name('your.teams.show');

});

    // Route::resource('messages', 'MessageBoardController');
