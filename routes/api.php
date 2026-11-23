<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerangkatController;
use App\Http\Middleware\AdminOnly;
use App\Http\Controllers\Api\SukuCadangController;
use App\Http\Controllers\Api\TiketServisController;


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


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/suku-cadang', [SukuCadangController::class, 'index']);

    Route::get('/suku-cadang/{kode}', [SukuCadangController::class, 'show']);

    Route::post('/suku-cadang', [SukuCadangController::class, 'store']);

    Route::put('/suku-cadang/{kode}', [SukuCadangController::class, 'update']);

    Route::delete('/suku-cadang/{kode}', [SukuCadangController::class, 'destroy'])
        ->middleware(\App\Http\Middleware\AdminOnly::class);

});



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/tiket-servis', [TiketServisController::class, 'index']);

    Route::get('/tiket-servis/{nomor_tiket}', [TiketServisController::class, 'show']);

    Route::post('/tiket-servis', [TiketServisController::class, 'store']);

    Route::put('/tiket-servis/{nomor_tiket}', [TiketServisController::class, 'update']);

    Route::delete('/tiket-servis/{nomor_tiket}', [TiketServisController::class, 'destroy'])
        ->middleware(\App\Http\Middleware\AdminOnly::class);
});

