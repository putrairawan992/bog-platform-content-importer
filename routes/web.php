<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{post}', [HomeController::class, 'show'])->name('post.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Posts Management
    Route::resource('posts', PostController::class);

    // Import
    Route::get('import', [ImportController::class, 'index'])->name('import.index');
    Route::post('import/manual', [ImportController::class, 'importManual'])->name('import.manual');
    Route::post('import/jsonplaceholder', [ImportController::class, 'importJsonPlaceholder'])->name('import.jsonplaceholder');
    Route::post('import/fakestore', [ImportController::class, 'importFakeStore'])->name('import.fakestore');
});
