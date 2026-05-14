<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Health Check Route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'TaxLegal API is running',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Public Blog Routes
Route::prefix('blog')->group(function () {
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/featured', [PostController::class, 'featured']);
    Route::get('/posts/popular', [PostController::class, 'popular']);
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    
    // Tags
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    
    // Comments (Public)
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
});

// Protected Admin Routes (Require Authentication)
Route::middleware('auth:sanctum')->prefix('admin/blog')->group(function () {
    // Posts Management
    Route::apiResource('posts', PostController::class);
    
    // Categories Management
    Route::apiResource('categories', CategoryController::class);
    
    // Tags Management
    Route::apiResource('tags', TagController::class);
    
    // Comments Management
    Route::apiResource('comments', CommentController::class);
    Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve']);
    Route::patch('/comments/{comment}/reject', [CommentController::class, 'reject']);
    Route::get('/comments/pending', [CommentController::class, 'pending']);

    // User Management
    Route::apiResource('users', UserController::class);
});

// Newsletter Routes (Public)
Route::prefix('newsletter')->group(function () {
    Route::post('/subscribe', [SubscriberController::class, 'store']);
    Route::post('/unsubscribe', [SubscriberController::class, 'unsubscribe']);
});

// Newsletter Admin Routes (Protected)
Route::middleware('auth:sanctum')->prefix('admin/newsletter')->group(function () {
    Route::get('/subscribers', [SubscriberController::class, 'index']);
    Route::delete('/subscribers/{id}', [SubscriberController::class, 'destroy']);
});

// Fallback for undefined API routes
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found',
        'status' => 404
    ], 404);
});
