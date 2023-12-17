<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('v1/register',[\App\Http\Controllers\AuthController::class,'dangky']);
Route::post('v1/login',[\App\Http\Controllers\AuthController::class,'login'])->name('login');
Route::post('v1/refresh',[\App\Http\Controllers\AuthController::class,'refresh']);
Route::prefix('v1')->middleware('jwt.auth')->group(function ()  {
   Route::post('getuser',[\App\Http\Controllers\AuthController::class,'getUser']);
   Route::post('logout',[\App\Http\Controllers\AuthController::class,'logout']);
});
