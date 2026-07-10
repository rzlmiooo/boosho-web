<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;

// Route API Search
Route::get('books/search/{title}', [BookController::class, 'search']);

// Public routes for reading books
Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show']);

// Protected routes for writing/deleting books
Route::middleware('auth:sanctum')->group(function () {
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);
    
    // (Opsional) Route untuk mendapatkan data user yang sedang login via token
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});