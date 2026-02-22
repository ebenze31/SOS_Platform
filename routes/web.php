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
        Route::get('/area/{id}/qrcode', 'AreasController@show_qrcode')->name('area.show_qrcode');

        // ============ Command monitor ============
        Route::get('/monitor', 'EmergencysController@monitor')->name('emergency.monitor');
        Route::get('/case_assign/{id}', 'EmergencysController@case_assign')->name('emergency.case_assign');
        Route::post('/emergency/complete/{id}', 'EmergencysController@completeCase')->name('emergency.complete');
    });

    // Admin, Officer
    Route::middleware(['role:admin,officer'])->group(function () {
        // ============ ลงทะเบียนเจ้าหน้าที่ ============
        Route::get('/officer/register', 'User_officersController@register_form')->name('officer.register');
        Route::post('/officer/register', 'User_officersController@register_store')->name('officer.store');
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
