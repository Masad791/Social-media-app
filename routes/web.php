<?php
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('welcome');
});

//Guest Route
Route::get('/guest/posts', [PostController::class, 'guestIndex'])->name('posts.guest');
Route::get('/posts/{post}/guest-ajax-comments', [CommentController::class, 'guestAjaxLoadComments'])->name('posts.guest-ajax-comments');

Route::get('/dashboard', [PostController::class, 'dashboard'])
->middleware('auth')->name('dashboard');

Route::get('/user-profile', function () {
    return view('user-profile');
});

Route::prefix('admin')
    ->middleware(['auth',IsAdmin::class])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // Later add resource routes:
        // Route::resource('users', Admin\UserController::class);
        // Route::resource('posts', Admin\PostController::class);
    });

//Posts Routes
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store'); 
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index'); // index
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    //Ajax for likes
    Route::post('/posts/{post}/ajax-like', [LikeController::class, 'ajaxToggleLike'])->name('posts.ajax-like');
   
    // AJAX routes for Comments   
    Route::post('/posts/{post}/ajax-comment', [CommentController::class, 'ajaxAddComment'])->name('posts.ajax-comment');
    Route::delete('/comments/{comment}', [CommentController::class, 'deleteComment'])->name('comments.delete');
    Route::get('/posts/{post}/ajax-comments', [CommentController::class, 'ajaxLoadComments'])->name('posts.ajax-comments');   
});

Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like')->middleware('auth');
Route::get('/comments/{comment}', [CommentController::class, 'edit'])->name('comments.edit')->middleware('auth');
Route::post('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update')->middleware('auth');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
