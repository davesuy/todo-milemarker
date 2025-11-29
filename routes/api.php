<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TodoController;
use Illuminate\Support\Facades\Route;

// API Health Check
Route::get('/', function () {
    return response()->json([
        'message' => 'Todo API is running',
        'version' => '1.0',
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'endpoints' => [
            'auth' => [
                'POST /api/register',
                'POST /api/login',
                'POST /api/logout (protected)',
                'GET /api/user (protected)',
            ],
            'categories' => [
                'GET /api/categories (protected)',
                'POST /api/categories (protected)',
                'GET /api/categories/{id} (protected)',
                'PUT /api/categories/{id} (protected)',
                'DELETE /api/categories/{id} (protected)',
            ],
            'todos' => [
                'GET /api/todos (protected)',
                'POST /api/todos (protected)',
                'GET /api/todos/{id} (protected)',
                'PUT /api/todos/{id} (protected)',
                'DELETE /api/todos/{id} (protected)',
                'POST /api/todos/{id}/toggle (protected)',
            ],
        ],
    ]);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Todo routes
    Route::apiResource('todos', TodoController::class);
    Route::post('/todos/{todo}/toggle', [TodoController::class, 'toggle']);

    // Category routes
    Route::apiResource('categories', CategoryController::class);
});

