<?php

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

Route::get('/image/{image}', 'ImageController@show');

Route::get('/webhooks/dropbox', 'Dropbox\WebhookController@show');
Route::post('/webhooks/dropbox', 'Dropbox\WebhookController@store');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/oauth/dropbox', 'Dropbox\OAuthControlle@show');
    Route::post('/oauth/dropbox', 'Dropbox\OAuthControlle@store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/test', function (Request $request) {
        return response()->json([], 419);
    });
});
