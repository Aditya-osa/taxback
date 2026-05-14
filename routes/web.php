<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BlogController::class, 'index'])->name('home');

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
    Route::post('/{post}/comments', [BlogController::class, 'storeComment'])->name('comments.store');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/feed', [BlogController::class, 'feed'])->name('feed');
    Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('sitemap');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.blog.index');
    });
    
    Route::prefix('blog')->name('blog.')->group(function () {
        // Posts
        Route::get('/', [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
        Route::post('/', [AdminBlogController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{post}', [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{post}', [AdminBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{post}/duplicate', [AdminBlogController::class, 'duplicate'])->name('duplicate');
        
        // Comments
        Route::get('/comments', [AdminBlogController::class, 'comments'])->name('comments');
        Route::post('/comments/{comment}/approve', [AdminBlogController::class, 'approveComment'])->name('comments.approve');
        Route::post('/comments/{comment}/reject', [AdminBlogController::class, 'rejectComment'])->name('comments.reject');
        Route::delete('/comments/{comment}', [AdminBlogController::class, 'deleteComment'])->name('comments.delete');
        
        // Categories
        Route::get('/categories', [AdminBlogController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminBlogController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminBlogController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminBlogController::class, 'deleteCategory'])->name('categories.delete');
        
        // Tags
        Route::get('/tags', [AdminBlogController::class, 'tags'])->name('tags');
        Route::post('/tags', [AdminBlogController::class, 'storeTag'])->name('tags.store');
        Route::put('/tags/{tag}', [AdminBlogController::class, 'updateTag'])->name('tags.update');
        Route::delete('/tags/{tag}', [AdminBlogController::class, 'deleteTag'])->name('tags.delete');
    });
});

// Fallback for API
Route::get('/api', function () {
    return response()->json([
        'message' => 'TaxLegal Blog API',
        'version' => '1.0.0',
        'endpoints' => [
            'blog' => route('blog.index'),
            'admin' => route('admin.blog.index'),
        ]
    ]);
});
