<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XatbotController;

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

Route::get('/activity', [XatbotController::class, 'updatedActivity']);
Route::get('/welcome', [XatbotController::class, 'welcome']);

Route::post('/3ymCKxba9W61ANhx03Ub7i8dOhOEsh4me5j2dOQGhgShGoxjlCivKF3uiUEThxBuZqN9nvM2tPg3UGze/webhook', [XatbotController::class, 'handleRequest']);

Route::get('/setwebhook', [XatbotController::class, 'setWebhook']);