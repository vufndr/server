<?php

use App\Http\Controllers\ImageDropboxController;
use App\Http\Controllers\OAuthDropboxController;
use App\Http\Controllers\WebhookDropboxController;
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

Route::get('/image/{image}', [ImageDropboxController::class, 'show']);

Route::get('/webhooks/dropbox', [WebhookDropboxController::class, 'show']);
Route::post('/webhooks/dropbox', [WebhookDropboxController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/test', function (Request $request) {
        return response()->json([], 419);
    });

    Route::get('/oauth/dropbox', [OAuthDropboxController::class, 'show']);
    Route::post('/oauth/dropbox', [OAuthDropboxController::class, 'store']);
});
