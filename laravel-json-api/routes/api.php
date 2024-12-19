<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use App\Http\Controllers\Api\V2\Auth\LoginController;
use App\Http\Controllers\Api\V2\Auth\LogoutController;
use App\Http\Controllers\Api\V2\Auth\RegisterController;
use App\Http\Controllers\Api\V2\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V2\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V2\MeController;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use App\Http\Controllers\Api\V2\Auth\VerificationController;
use App\Http\Controllers\Api\V2\UserController;
use App\Http\Controllers\Api\V2\VbotController;
use App\Http\Controllers\Api\V2\ChangePasswordController;
use App\Http\Controllers\Api\V2\dashboard;
use App\Http\Controllers\Api\V2\CalllogController;
use App\Http\Controllers\Api\V2\CallViewController;
use App\Http\Controllers\Api\V2\SmsController;
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

Route::prefix('v2')->middleware('json.api')->group(function () {
    Route::post('/login', LoginController::class)->name('login');
    Route::post('/logout', LogoutController::class)->middleware('auth:api');
    Route::post('/register', RegisterController::class);
    Route::post('/password-forgot', ForgotPasswordController::class);

    Route::post('/password-reset', ResetPasswordController::class)->name('password.reset');
    Route::get('/auth/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
    
});

JsonApiRoute::server('v2')->prefix('v2')->resources(function (ResourceRegistrar $server) {
    $server->resource('users', JsonApiController::class);
    Route::get('me', [MeController::class, 'readProfile'])->middleware('auth:api');
    Route::patch('me', [MeController::class, 'updateProfile'])->middleware('auth:api');
    Route::post('user', [UserController::class, 'addUser'])->middleware('auth:api');
    Route::get('user', [UserController::class, 'getUser'])->middleware('auth:api');
    Route::delete('user/{id}', [UserController::class, 'deleteUser'])->middleware('auth:api');
    Route::get('user/{id}', [UserController::class, 'getUserById'])->middleware('auth:api');
    Route::patch('user/{id}', [UserController::class, 'updateUser'])->middleware('auth:api');
    Route::post('vbot', [VbotController::class, 'addVbot'])->middleware('auth:api');
    Route::get('vbot', [VbotController::class, 'getVbot'])->middleware('auth:api');
    Route::delete('vbot/{id}', [VbotController::class, 'destroy'])->middleware('auth:api');
    Route::get('vbot/{id}', [VbotController::class, 'getVbotById'])->middleware('auth:api');
    Route::patch('vbot/{id}', [VbotController::class, 'updateVbot'])->middleware('auth:api');
    Route::post('change', [ChangePasswordController::class, 'updatePassword'])->middleware('auth:api');
    Route::get('dashboard', [dashboard::class, 'getUserCount'])->middleware('auth:api');
   
    Route::get('calls', [CalllogController::class, 'getCalls'])->middleware('auth:api');
    Route::delete('calls/{id}', [CalllogController::class, 'deleteCalls'])->middleware('auth:api');
    Route::get('fetch-phonebot-settings/{user_id}', [CalllogController::class, 'fetchPhonebotSettings']);

// Store call logs for the given user
    Route::post('send-call-logs/{phonebot_id}', [CalllogController::class, 'storeCallLogs']);
    Route::post('send-status/{phonebot_id}', [CalllogController::class, 'storestatus']);
    Route::get('phonebot', [VbotController::class, 'getPhonebot'])->middleware('auth:api');
    Route::patch('phonebot/{id}', [VbotController::class, 'updatePhonebot'])->middleware('auth:api');
    Route::patch('profile', [VbotController::class, 'updateProfile'])->middleware('auth:api');
    Route::get('role', [dashboard::class, 'getRole'])->middleware('auth:api');

    Route::get('callsview', [CallViewController::class, 'callsview']);
    Route::patch('updateCallStatus/{id}', [CallViewController::class, 'updateCallStatus']);
    Route::patch('updateBulkStatus', [CallViewController::class, 'updateBulkStatus']);
    Route::get('notes/{id}', [CallViewController::class, 'getNotes']);
    Route::post('notes/{id}', [CallViewController::class, 'addNotes']);
    Route::get('newcalls', [CallViewController::class, 'newCalls']);
    Route::patch('updateNotificationStatus', [CallViewController::class, 'updateNotificationStatus']);
    Route::patch('updateCallStatusCompleted/{id}', [CallViewController::class, 'updateCallStatusCompleted']);

    Route::post('sendSms', [SmsController::class, 'sendSms']);

    
});