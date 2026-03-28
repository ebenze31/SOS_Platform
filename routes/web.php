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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/demo/assign', function () {
//     return view('demo/assign');
// });
// Route::get('/demo/sos_main', function () {
//     return view('demo/sos_main');
// });
// Route::get('/demo/status_update', function () {
//     return view('demo/status_update');
// });
// Route::get('/demo/rate', function () {
//     return view('demo/rate');
// });
// Route::get('/demo/case_assign', function () {
//     return view('demo/case_assign');
// });
// Route::get('/demo/register_scan', function () {
//     return view('demo/register_scan');
// });
// Route::get('/demo/register_form', function () {
//     return view('demo/register_form');
// });
// Route::get('/demo/area_main', function () {
//     return view('demo/area_main');
// });
// Route::get('/demo/main_officer', function () {
//     return view('demo/main_officer');
// });
// Route::get('/demo/all_officer', function () {
//     return view('demo/all_officer');
// });
// Route::get('/demo/switch_status', function () {
//     return view('demo/switch_status');
// });
// Route::get('/demo/profile', function () {
//     return view('demo/profile');
// });
Route::get('/demo/history', function () {
    return view('demo/history');
});
Route::get('/demo/dashboard', function () {
    return view('demo/dashboard');
});

// หน้า case_assign เมื่อปิดเคส "เวลาที่ผ่านไป" ให้เป็น "ใช้เวลาสุทธิ" หรือซ่อน
// เช็คเส้นทางไม่วาดในหน้า case_assign
// สถานะเสร็จสิ้น card info หน้า case_assign ยังไม่เปลี่ยน Real-time

// ปรับการแสดงผลหน้า monitor ให้แสดงคนสั่งการและข้อมูลอื่นๆ รวมถึงกดดูเคสเสร็จสิ้นได้

// อย่าลืมเพิ่ม amount_help ตอนเจ้าหน้าที่กดรับงาน
// อย่าลืมจัดการการปฏิเสธเคสของเจ้าหน้าที่

// หน้า Map ดำเนินการ อย่าลืมลูปรับตำแหน่งเจ้าหน้าที่
// หน้า Map ดำเนินการ เจ้าหน้าที่ปุ่มกล้องลอยทับ navbar
// หน้า Map ดำเนินการ จัดการการแสดงผลหมุดและ map หลังเสร็จสิ้น

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

// ส่งผู้ใช้ไปหน้าล็อกอินของ LINE
Route::get('/login/line', 'Auth\LoginController@redirectToLine')->name('login.line');
// รับข้อมูลกลับมาจาก LINE หลังจากล็อกอินเสร็จ
Route::get('/login/line/callback', 'Auth\LoginController@handleLineCallback')->name('login.line.callback');


// -------------------------- middleware -------------------------- //
Route::middleware(['auth'])->group(function () {

    // ============ โปรไฟล์ ============
    Route::get('/profile', 'UserController@index');

    // ============ ลงทะเบียนเจ้าหน้าที่ ============
    Route::get('/user_officers/scan', 'User_officersController@scan_area')->name('user_officers.scan');
    Route::get('/user_officers/register', 'User_officersController@register_form')->name('user_officers.register');
    Route::post('/user_officers/register', 'User_officersController@register_store')->name('user_officers.register_store');

    // ประวัติการขอความช่วยเหลือ
    Route::get('/sos/history', 'EmergencysController@history')->name('emergency.history');

    // หน้าขอความช่วยเหลือ
    Route::get('/sos', 'EmergencysController@index')->name('emergency.index');
    Route::post('/sos/send', 'EmergencysController@store')->name('emergency.store');

    // หน้าติดตามสถานะ
    Route::get('/sos/tracking/{id}', 'EmergencysController@tracking')->name('emergency.tracking');
    Route::get('/emergency/tracking/api/{id}', 'EmergencysController@checkStatus')->name('emergency.checkStatus');

    // หน้าประเมินการบริการ
    Route::get('/sos/rate/{id}', 'EmergencysController@showRatePage')->name('emergency.rate');
    Route::post('/sos/rate/{id}', 'EmergencysController@submitRate')->name('emergency.rate.submit');

    // Admin
    Route::middleware(['role:admin'])->group(function () {
        // ================= Areas =================
        Route::get('/area/area_main', 'AreasController@area_main');
        Route::get('/area/create_polygon', 'AreasController@create_polygon')->name('area.create_polygon');
        Route::post('/area/store_polygon', 'AreasController@store_polygon')->name('area.store_polygon');
        Route::get('/area/{id}/manage', 'AreasController@manage_area')->name('area.manage_area');
        Route::post('/area/{id}/manage/update', 'AreasController@update_manage_area')->name('area.update_manage');
        Route::post('/area/{id}/toggle-status', 'AreasController@toggle_status')->name('area.toggle_status');

        // ============ Command monitor ============
        Route::get('/monitor', 'EmergencysController@monitor')->name('emergency.monitor');
        Route::get('/case_assign/{id}', 'EmergencysController@case_assign')->name('emergency.case_assign');
        Route::post('/emergency/complete/{id}', 'EmergencysController@completeCase')->name('emergency.complete');
        Route::post('/emergencys/assign/{id}', 'EmergencysController@assign_officer')->name('emergency.assign');

        // ======== Command Check requests ========
        Route::get('/command/requests', 'CommandController@index')->name('command.requests');
        Route::post('/command/requests/manage', 'CommandController@manage_request')->name('command.requests.manage');

        // ======== Emergency Types ========
        Route::get('/emergency-types', 'Emergency_typesController@index');
        Route::post('/emergency-types/store', 'Emergency_typesController@store');
        Route::post('/emergency-types/destroy', 'Emergency_typesController@destroy');
        Route::post('/emergency-types/update-status', 'Emergency_typesController@updateStatus');

        // ======== Phone Emergencies ========
        Route::get('/phone-emergencys', 'Phone_emergencysController@index');
        Route::post('/phone-emergencys/store', 'Phone_emergencysController@store');
        Route::post('/phone-emergencys/destroy', 'Phone_emergencysController@destroy');
        Route::post('/phone-emergencys/update-status', 'Phone_emergencysController@updateStatus');
        Route::post('/phone-emergencys/update-priority', 'Phone_emergencysController@updatePriority');

        // ======== การจัดการสมาชิก ========
        Route::get('/members', 'MemberController@index')->name('members.index');
        Route::post('/members/command', 'MemberController@storeCommand')->name('members.command.store');
        Route::post('/members/toggle-status/{id}', 'MemberController@toggleStatus')->name('members.toggle-status');
        Route::post('/members/command/toggle-status/{id}', 'MemberController@toggleCommandStatus')->name('members.command.toggle-status');
        Route::post('/members/officer/toggle-status/{id}', 'MemberController@toggleOfficerStatus')->name('members.officer.toggle-status');
    });

    // Admin, Officer
    Route::middleware(['role:admin,officer'])->group(function () {
        // ============  หน้าเปิดสถานะเจ้าหน้าที่ ============
        Route::get('/officer/open_status', 'User_officersController@showStatus')->name('officer.status');
        Route::post('/officer/update-status', 'User_officersController@updateStatusStandby');
        Route::post('/officer/update-status-case-success', 'User_officersController@updateStatus_CaseSuccess');
        Route::post('/officer/update-location', 'User_officersController@updateLocationOnly');

        // ============  Map ดำเนินการช่วยเหลือ ============
        Route::get('/officer/action/{id}', 'User_officersController@actionPage')->name('officer.action');
        Route::post('/officer/action/update/{id}', 'User_officersController@updateStatus')->name('officer.action.update');
        Route::post('/officer/action/upload-photo/{id}', 'User_officersController@uploadPhoto')->name('officer.action.upload_photo');
        Route::post('/officer/sync-operation', 'User_officersController@syncOperation');

        // ============ ประวัติการช่วยเหลือ ============
        Route::get('/officer/officer_history', 'User_officersController@officer_history');

    });
});
// ----------------------- End middleware -------------------------- //

// Route::resource('user_officers', 'User_officersController');
// Route::resource('emergency_types', 'Emergency_typesController');
// Route::resource('emergencys', 'EmergencysController');
// Route::resource('emergency_operations', 'Emergency_operationsController');
// Route::resource('phone_emergencys', 'Phone_emergencysController');
// Route::resource('areas', 'AreasController');
// Route::resource('my_log', 'My_logController');
Route::resource('data_organizations', 'Data_organizationsController');
Route::resource('user_commands', 'User_commandsController');
Route::resource('profile', 'UserController');
