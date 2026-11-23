<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/logout', [AuthController::class, 'logout']);

});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/perangkat', [
        \App\Http\Controllers\Api\PerangkatController::class,
        'index'
    ]);

    Route::get('/perangkat/{nomor_seri}', [
        \App\Http\Controllers\Api\PerangkatController::class,
        'show'
    ]);

    Route::middleware(\App\Http\Middleware\AdminOnly::class)
        ->group(function () {

            Route::post('/perangkat', [
                \App\Http\Controllers\Api\PerangkatController::class,
                'store'
            ]);

            Route::put('/perangkat/{nomor_seri}', [
                \App\Http\Controllers\Api\PerangkatController::class,
                'update'
            ]);

            Route::delete('/perangkat/{nomor_seri}', [
                \App\Http\Controllers\Api\PerangkatController::class,
                'destroy'
            ]);
        });
});

