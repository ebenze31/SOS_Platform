<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/demo/assign', function () {
    return view('demo/assign');
});
Route::get('/demo/sos_main', function () {
    return view('demo/sos_main');
});
Route::get('/demo/status_update', function () {
    return view('demo/status_update');
});
Route::get('/demo/rate', function () {
    return view('demo/rate');
});
Route::get('/demo/case_assign', function () {
    return view('demo/case_assign');
});
Route::get('/demo/register_scan', function () {
    return view('demo/register_scan');
});
Route::get('/demo/register_form', function () {
    return view('demo/register_form');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// -------------------------- middleware -------------------------- //
Route::middleware(['auth'])->group(function () {

    // หน้าขอความช่วยเหลือ
    Route::get('/sos', 'EmergencysController@index')->name('emergency.index');
    Route::post('/sos/send', 'EmergencysController@store')->name('emergency.store');

    // หน้าติดตามสถานะ
    Route::get('/sos/tracking/{id}', 'EmergencysController@tracking')->name('emergency.tracking');

    // Admin
    Route::middleware(['role:admin'])->group(function () {
        // ================= Areas =================
        Route::get('/area/create_polygon', 'AreasController@create_polygon')->name('area.create_polygon');
        Route::post('/area/store_polygon', 'AreasController@store_polygon')->name('area.store_polygon');
        Route::get('/area/{id}/manage', 'AreasController@manage_area')->name('area.manage_area');
        Route::post('/area/{id}/manage/update', 'AreasController@update_manage_area')->name('area.update_manage');

        // ============ Command monitor ============
        Route::get('/monitor', 'EmergencysController@monitor')->name('emergency.monitor');
        Route::get('/case_assign/{id}', 'EmergencysController@case_assign')->name('emergency.case_assign');
        Route::post('/emergency/complete/{id}', 'EmergencysController@completeCase')->name('emergency.complete');
        Route::post('/emergencys/assign/{id}', 'EmergencysController@assign_officer')->name('emergency.assign');

        // ======== Command Check requests ========
        Route::get('/command/requests', 'CommandController@index')->name('command.requests');
        Route::post('/command/requests/manage', 'CommandController@manage_request')->name('command.requests.manage');
    });

    // Admin, Officer
    Route::middleware(['role:admin,officer'])->group(function () {
        // ============ หน้าสแกน / เลือกพื้นที่ ============
        Route::get('/user_officers/scan', 'User_officersController@scan_area')->name('user_officers.scan');

        // ============ ลงทะเบียนเจ้าหน้าที่ ============
        Route::get('/user_officers/register', 'User_officersController@register_form')->name('user_officers.register');
        Route::post('/user_officers/register', 'User_officersController@register_store')->name('user_officers.register_store');
    });
});
// ----------------------- End middleware -------------------------- //

Route::resource('data_organizations', 'Data_organizationsController');
Route::resource('user_commands', 'User_commandsController');
Route::resource('user_officers', 'User_officersController');
Route::resource('areas', 'AreasController');
Route::resource('phone_emergencys', 'Phone_emergencysController');
Route::resource('emergency_types', 'Emergency_typesController');
Route::resource('my_log', 'My_logController');
Route::resource('emergencys', 'EmergencysController');
Route::resource('emergency_operations', 'Emergency_operationsController');
