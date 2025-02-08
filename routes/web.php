<?php

use App\Http\Controllers\Admin\LocatedAreaController;
use App\Http\Controllers\Admin\LocatedCountryController;
use App\Http\Controllers\Admin\LocatedRegionController;
use App\Http\Controllers\Admin\LocatedVillageController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\RolesController as AdminRolesController;
use App\Http\Controllers\Admin\PermissionsController as AdminPermissionsController;
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
        Route::resource('/roles', AdminRolesController::class)->except(['show']);
        Route::resource('/permissions', AdminPermissionsController::class)->except(['show']);
        Route::prefix('located')->name('located.')->group(function () {
            Route::resource('/countries', LocatedCountryController::class);
            Route::resource('/regions', LocatedRegionController::class);
            Route::resource('/areas', LocatedAreaController::class);
            Route::resource('/villages', LocatedVillageController::class);
        });
    });

    Route::get('/admin/locations', [LocationController::class, 'index'])->name('admin.locations.index');

    Route::get('/admin/locations/countries', [LocationController::class, 'getCountries'])->name('admin.locations.countries');
    Route::get('/admin/locations/regions/{countryId}', [LocationController::class, 'getRegions'])->name('admin.locations.regions');
    Route::get('/admin/locations/areas/{regionId}', [LocationController::class, 'getAreas'])->name('admin.locations.areas');
    Route::get('/admin/locations/villages/{areaId}', [LocationController::class, 'getVillages'])->name('admin.locations.villages');

    Route::post('/admin/locations/store', [LocationController::class, 'store'])->name('admin.locations.store');
    Route::delete('/admin/locations/destroy/{id}', [LocationController::class, 'destroy'])->name('admin.locations.destroy');
    Route::get('/admin/locations/{type}/{id}', [LocationController::class, 'show'])->name('admin.locations.show');

});

require __DIR__.'/auth.php';
