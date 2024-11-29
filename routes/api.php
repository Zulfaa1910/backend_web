<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserSalesController;
use App\Http\Controllers\UserResellerController;
use App\Http\Controllers\TaskPekerjaanController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

Route::post('register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [App\Http\Controllers\AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/users-sales', [UserSalesController::class, 'index']);
        Route::post('/users-sales', [UserSalesController::class, 'create']);
        Route::get('/users-sales/{id}', [UserSalesController::class, 'show']);
        Route::put('/users-sales/{id}', [UserSalesController::class, 'update']);
        Route::delete('/users-sales/{id}', [UserSalesController::class, 'destroy']);

        Route::get('/users-reseller', [UserResellerController::class, 'index']);   // Mendapatkan daftar reseller
        Route::post('/users-reseller', [UserResellerController::class, 'store']);  // Menambahkan reseller baru
        Route::get('/users-reseller/{id}', [UserResellerController::class, 'show']); // Mendapatkan reseller berdasarkan ID
        Route::put('/users-reseller/{id}', [UserResellerController::class, 'update']); // Mengupdate reseller
        Route::delete('/users-reseller/{id}', [UserResellerController::class, 'destroy']); // Menghapus reseller

        Route::resource('tasks', TaskPekerjaanController::class);
});
