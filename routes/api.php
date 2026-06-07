<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;

// Route API Search
Route::get('books/search/{title}', [BookController::class, 'search']);

// Endpoint khusus yang mengarah ke BookController
Route::apiResource('books', BookController::class);

// (Opsional) Route untuk mendapatkan data user yang sedang login via token
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');