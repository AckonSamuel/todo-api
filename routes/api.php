<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TodoController as TodoControllerV1;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('todos', [TodoControllerV1::class, 'index']);
    Route::post('todos', [TodoControllerV1::class, 'store']);
    Route::get('todos/{id}', [TodoControllerV1::class, 'show']);
    Route::put('todos/{id}', [TodoControllerV1::class, 'update']);
    Route::delete('todos/{id}', [TodoControllerV1::class, 'destroy']);
});