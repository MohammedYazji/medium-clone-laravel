<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/@{user:username}',[PublicProfileController::class, 'show'])->name('profile.show');


// home page
Route::get('/', [PostController::class, 'index'])->name('dashboard');

// category pages
Route::get('/category/{category}', [PostController::class, 'category'])->name('post.byCategory');

// display the post details page
Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])->name('post.show');


Route::middleware(['auth', 'verified'])->group(function () {

    // display form to create post
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');

    // create and store the post
    Route::post('/post/create', [PostController::class, 'store'])->name('post.store');

    // Display form to edit post
    Route::get('/post/{post:slug}', [PostController::class, 'edit'])->name('post.edit');

    // Update the post
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');

    // delete the post
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    // display auth user posts page
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('myPosts');


    // handle follow & unfollow
    Route::post('/follow/{user}', [FollowerController::class, 'followUnfollow'])->name('follow');

    // handle like & unlike
    Route::post('/clap/{post}', [ClapController::class,'clap'])->name('clap');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
