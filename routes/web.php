<?php


use App\Http\Controllers\Admin\Adverts\AdvertsController;
use App\Http\Controllers\Admin\Adverts\AttributeController;
use App\Http\Controllers\Admin\Adverts\CategoryController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PermissionsController as AdminPermissionsController;
use App\Http\Controllers\Admin\RolesController as AdminRolesController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Cabinet\Adverts\AdvertController;
use App\Http\Controllers\Cabinet\Chat\ChatController;
use App\Http\Controllers\Cabinet\Profile\PhoneController;
use App\Http\Controllers\Cabinet\Profile\ProfileController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;


Route::get('/', [IndexController::class, 'index'])->middleware([])->name('main');
Route::prefix('/adverts')->name('adverts.')->group(function () {
    Route::get('/show/{advert}', [IndexController::class, 'show'])->name('show');
    Route::get('/phone/{advert}', [IndexController::class, 'phone'])->name('phone');
    Route::get('/regions', [IndexController::class, 'regions'])->name('regions');
    Route::get('/regions/{region}/cities', [IndexController::class, 'cities'])->name('cities');
    Route::get('/regions-search/{region}', [IndexController::class, 'search'])->name('regions.search');
//    Route::get('{categorySlug}/{subCategorySlug?}/{sub2CategorySlug?}/{citySlug?}', [IndexController::class, 'showCategory'])->name('category.show')
//        ->where([
//            'categorySlug' => '[a-z0-9-]+',
//            'subCategorySlug' => '[a-z0-9-]+',
//            'sub2CategorySlug' => '[a-z0-9-]+',
//            'citySlug' => '[a-z0-9-]+'
//        ]);
    Route::get('{urlPath?}', [IndexController::class, 'showCategory'])
        ->where('urlPath', '[a-z0-9-\/]+') // Дозволяємо передавати категорії через `/`
//        ->where('citySlug', '[a-z0-9-]+')
        ->name('category.show');

});
Route::get('/greeting/{locale}', [IndexController::class, 'changeLocale'])->name('greeting');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/account')->name('account.')->group(function () {
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard'); //прибрати потім
        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/phone', [PhoneController::class, 'request'])->name('phone.request');
            Route::get('/edit/phone', [PhoneController::class, 'form'])->name('phone.form');
            Route::put('/edit/phone', [PhoneController::class, 'verify'])->name('phone.verify');
            Route::patch('/update/phone', [PhoneController::class, 'update'])->name('phone.update');

            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('/adverts')->name('adverts.')->group(function () {
            Route::get('/', [AdvertController::class, 'index'])->name('index');
            Route::get('/show/{advert}', [IndexController::class, 'show'])->name('show');
            Route::get('/edit/{advert}', [AdvertController::class, 'edit'])->name('edit');
            Route::delete('/destroy/{advert}', [AdvertController::class, 'destroy'])->name('destroy');
            Route::get('/edit/{advert}/photos', [AdvertController::class, 'photos'])->name('edit.photos');
            Route::post('/publish/{advert}', [AdvertController::class, 'publish'])->name('actions.publish');
            Route::post('/draft/{advert}', [AdvertController::class, 'draft'])->name('actions.draft');
            Route::get('/create', [AdvertController::class, 'create'])->name('create');
            Route::post('/store', [AdvertController::class, 'store'])->name('store');
            Route::get('/areas/{regionId}', [AdvertController::class, 'getAreas'])->name('areas');
            Route::get('/villages/{areaId}', [AdvertController::class, 'getVillages'])->name('villages');
            Route::get('/attributes/{categoryId}', [AdvertController::class, 'getAttributes'])->name('attributes');

        });
        Route::prefix('/chats')->name('chats.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
        });

    });

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/', [AdminIndexController::class, 'index'])->name('index');
        Route::get('/test', [AdminIndexController::class, 'test'])->name('test');

        Route::controller(AdminUsersController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/search', 'search')->name('search');
            Route::put('/{user}/restore', 'restore')->name('restore');
        });
        Route::resource('/users', AdminUsersController::class);
        Route::resource('/roles', AdminRolesController::class)->except(['show']);
        Route::resource('/permissions', AdminPermissionsController::class)->except(['show']);

        Route::prefix('/locations')->name('locations.')->controller(LocationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/countries', 'getCountries')->name('countries');
            Route::get('/regions/{countryId}', 'getRegions')->name('regions');
            Route::get('/areas/{regionId}', 'getAreas')->name('areas');
            Route::get('/villages/{areaId}', 'getVillages')->name('villages');
            Route::post('/store', 'store')->name('store');
            Route::delete('/destroy/{type}/{id}', 'destroy')->name('destroy');

            Route::get('/{type}/{id}', 'show')->name('show');
        });

        Route::prefix('/adverts')->name('adverts.')->group(function () {

            Route::get('/moderation', [AdvertsController::class, 'moderation'])->name('actions.moderation');
            Route::post('/moderation/{advert}/active', [AdvertsController::class, 'active'])->name('actions.moderation.active');
            Route::post('/moderation/{advert}/reject', [AdvertsController::class, 'reject'])->name('actions.moderation.reject');

            Route::get('/category/{category}/attributes/create', [AttributeController::class, 'create'])->name('category.attributes.create');
            Route::post('/category/{category}/attributes/store', [AttributeController::class, 'store'])->name('category.attributes.store');
            Route::get('/category/{category}/attributes/{attribute}/edit', [AttributeController::class, 'edit'])->name('category.attributes.edit');
            Route::post('/category/{category}/attributes/{attribute}/update', [AttributeController::class, 'update'])->name('category.attributes.update');
            Route::delete('/category/{category}/attributes/{attribute}/destroy', [AttributeController::class, 'destroy'])->name('category.attributes.destroy');
            Route::resource('/category', CategoryController::class);

            Route::prefix('/category')->name('category.')->controller(CategoryController::class)->group(function () {
                Route::post('/{category}/move-up', 'moveUp')->name('moveUp');
                Route::post('/{category}/move-down', 'moveDown')->name('moveDown');
                Route::post('/{category}/move-to-top', 'moveToTop')->name('moveToTop');
                Route::post('/{category}/move-to-bottom', 'moveToBottom')->name('moveToBottom');
            });
        });
    });

});

require __DIR__.'/auth.php';
