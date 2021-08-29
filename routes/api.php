<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
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

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [AuthController::class, 'all']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'files', 'middleware', 'auth:sanctum'], function () {
    Route::get('/', [FileController::class, 'index']);
    Route::get('/{file}', [FileController::class, 'show']);
    Route::post('/', [FileController::class, 'store']);
    Route::delete('/{file}', [FileController::class, 'destroy']);
});

Route::group(['prefix' => 'devices'], function () {
    Route::get('/', [DeviceController::class, 'index']);
    Route::post('/', [DeviceController::class, 'store'])->middleware('admin');
});
