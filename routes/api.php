<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhook', 'LineWebhookController@handle');

Route::get('/check-notifications', 'NotificationController@check');
Route::post('/mark-notifications-alert', 'NotificationController@markAlert');

Route::get('/emergency/tracking/{operation_id}', 'EmergencysController@trackingData');
Route::get('/emergency/{emergency_id}/operation', 'EmergencysController@getOperationData');
Route::post('/emergency/{emergency_id}/update-route-log', 'EmergencysController@updateRouteLog');

Route::get('/area/refreshGroupLine', 'AreasController@get_groups_ajax')->name('groups.ajax');