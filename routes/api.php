<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Support\Facades\Route;

Route::middleware('set.locale')->group(function () {

    

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);




    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [
            AuthController::class,
            'logout'
        ]);

        Route::get('/books', [
            BookController::class,
            'index'
        ]);

        Route::get('/books/{id}', [
            BookController::class,
            'show'
        ])->whereNumber('id');

        Route::post('/books', [
            BookController::class,
            'store'
        ]);

        Route::delete('/books/{id}', [
            BookController::class,
            'destroy'
        ])->whereNumber('id');

    });

});