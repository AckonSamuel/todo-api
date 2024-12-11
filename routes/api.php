<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\V1\TodoController as TodoControllerV1;

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

Route::prefix('v1')->group(function () {
    Route::get('todos', [TodoControllerV1::class, 'index']);
    Route::post('todos', [TodoControllerV1::class, 'store']);
    Route::get('todos/{id}', [TodoControllerV1::class, 'show']);
    Route::put('todos/{id}', [TodoControllerV1::class, 'update']);
    Route::delete('todos/{id}', [TodoControllerV1::class, 'destroy']);
});


