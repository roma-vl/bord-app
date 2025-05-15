<?php

use App\Http\Controllers\Api\Adverts\AdvertController;
use App\Http\Controllers\Api\Adverts\FavoriteController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Cabinet\AdvertController as CabinetAdvertController;
use App\Http\Controllers\Api\Cabinet\FavoriteController as CabinetFavoriteController;
use App\Http\Controllers\Api\Cabinet\ProfileController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;


Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware(['throttle', 'api'])->name('passport.token');

Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'register']);
    Route::get('/home', [HomeController::class, 'home']);

    Route::middleware('auth:api')->group(function () {
        Route::resource('adverts', AdvertController::class)->only('index', 'show');
        Route::post('/adverts/{advert}/favorite', [FavoriteController::class, 'add']);
        Route::delete('/adverts/{advert}/favorite', [FavoriteController::class, 'remove']);

        Route::prefix('user')->as('user.')->group(function () {
            Route::get('/show', [ProfileController::class, 'show'])->name('show');
            Route::patch('/update', [ProfileController::class, 'update'])->name('update');

            Route::get('/favorites', [CabinetFavoriteController::class, 'index'])->name('favorites.index');
            Route::delete('/favorites/{advert}', [CabinetFavoriteController::class, 'remove'])->name('favorites.remove');

            Route::resource('adverts', CabinetAdvertController::class)->only('index', 'show', 'update', 'destroy');
            Route::post('/adverts/create/{category}/{region?}', [CabinetAdvertController::class, 'store'])->name('adverts.create');

            Route::put('/adverts/{advert}/photos', [CabinetAdvertController::class, 'photos'])->name('adverts.photos');
            Route::put('/adverts/{advert}/attributes', [CabinetAdvertController::class, 'attributes'])->name('adverts.attributes');
            Route::post('/adverts/{advert}/send', [CabinetAdvertController::class, 'send'])->name('adverts.send');
            Route::post('/adverts/{advert}/close', [CabinetAdvertController::class, 'close'])->name('adverts.close');
        });
    });

});
