<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('main');

Route::get('/greeting/{locale}', [IndexController::class, 'changeLocale'])->name('greeting');

Route::get('/dashboard', [IndexController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () { //, PasswordConfirmed::class
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminIndexController::class, 'index'])->name('index');
        Route::get('/test', [AdminIndexController::class, 'test'])->name('test');

        Route::get('/users/search', [AdminUsersController::class, 'search'])->name('users.search');
        Route::put('/users/{user}/restore', [AdminUsersController::class, 'restore'])->name('users.restore');

        Route::resource('/users', AdminUsersController::class);

    });
});

require __DIR__.'/auth.php';
