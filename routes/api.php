<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerangkatController;
use App\Http\Middleware\AdminOnly;

// Endpoint publik (tanpa login)
Route::post('/login', [AuthController::class, 'login']);

// Endpoint yang memerlukan login
Route::middleware('auth:sanctum')->group(function () {

    // Profile dan Logout
    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // GET: Admin dan User
    Route::get('/perangkat', [PerangkatController::class, 'index']);
    Route::get('/perangkat/{nomor_seri}', [PerangkatController::class, 'show']);

    // POST: Admin dan User
    Route::post('/perangkat', [PerangkatController::class, 'store']);

    // PUT: Admin dan User (ownership dicek di Controller)
    Route::put('/perangkat/{nomor_seri}', [PerangkatController::class, 'update']);

    // DELETE: Hanya Admin
    Route::delete('/perangkat/{nomor_seri}', [PerangkatController::class, 'destroy'])
        ->middleware(AdminOnly::class);

});
