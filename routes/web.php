<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\Adverts\AdvertsController;
use App\Http\Controllers\Admin\Adverts\AttributeController;
use App\Http\Controllers\Admin\Adverts\CategoryController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PermissionsController as AdminPermissionsController;
use App\Http\Controllers\Admin\RolesController as AdminRolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BannerController as PublicBannerController;
use App\Http\Controllers\Cabinet\Adverts\AdvertController;
use App\Http\Controllers\Cabinet\Adverts\FavoriteController;
use App\Http\Controllers\Cabinet\Banner\BannerController;
use App\Http\Controllers\Cabinet\Banner\CreateController;
use App\Http\Controllers\Cabinet\Chat\ChatController;
use App\Http\Controllers\Cabinet\Profile\PhoneController;
use App\Http\Controllers\Cabinet\Profile\ProfileController;
use App\Http\Controllers\Cabinet\TicketController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StaticController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('/banner/get', [PublicBannerController::class, 'get'])->name('banner.get');
Route::get('/banner/{banner}/click', [PublicBannerController::class, 'click'])->name('banner.click');

Route::get('/', [IndexController::class, 'index'])->middleware([])->name('main');
Route::prefix('/adverts')->name('adverts.')->group(function () {
    Route::get('/show/{advert}', [IndexController::class, 'show'])->name('show');
    Route::get('/phone/{advert}', [IndexController::class, 'phone'])->name('phone');
    Route::get('/regions', [IndexController::class, 'regions'])->name('regions');
    Route::get('/regions/{region}/cities', [IndexController::class, 'cities'])->name('cities');
    Route::get('/regions-search/{region}', [IndexController::class, 'search'])->name('regions.search');
});
Route::get('/greeting/{locale}', [IndexController::class, 'changeLocale'])->name('greeting');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/account')->name('account.')->group(function () {
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

        Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('favorites/{advert}', [FavoriteController::class, 'add'])->name('favorites.add');
        Route::delete('favorites/{advert}', [FavoriteController::class, 'remove'])->name('favorites.remove');

        Route::resource('tickets', TicketController::class);
        Route::post('tickets/{ticket}/message', [TicketController::class, 'message'])->name('tickets.message');

        Route::prefix('/adverts')->name('adverts.')->group(function () {
            Route::get('/', [AdvertController::class, 'index'])->name('index');
            Route::get('/show/{advert}', [IndexController::class, 'show'])->name('show');
            Route::get('/edit/{advert}', [AdvertController::class, 'edit'])->name('edit');
            Route::post('/update/{advert}', [AdvertController::class, 'update'])->name('update');
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

        Route::prefix('/banners')->name('banners.')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('index');
            Route::get('/create', [CreateController::class, 'category'])->name('create');
            Route::get('/regions/{category}/{region?}', [CreateController::class, 'getRegions'])->name('regions');
            Route::get('/attributes/{category}', [CreateController::class, 'getAttributes'])->name('attributes');
            Route::post('/store', [CreateController::class, 'store'])->name('store');

            Route::get('/show/{banner}', [BannerController::class, 'show'])->name('show');
            Route::get('/edit/{banner}', [BannerController::class, 'edit'])->name('edit');
            Route::post('/update/{banner}', [BannerController::class, 'update'])->name('update');
            Route::get('/edit/{banner}/file', [BannerController::class, 'fileForm'])->name('edit.file');
            Route::post('/edit/{banner}/file', [BannerController::class, 'fileUpdate'])->name('edit.file.update');
            Route::post('/publish/{banner}', [BannerController::class, 'send'])->name('actions.publish');
            Route::post('/draft/{banner}', [BannerController::class, 'cancel'])->name('actions.draft');
            Route::post('/order/{banner}', [BannerController::class, 'order'])->name('actions.order');
            Route::delete('/destroy/{banner}', [BannerController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('/chats')->name('chats.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
        });

    });

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/', [AdminIndexController::class, 'index'])->name('index');
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

        Route::resource('pages', AdminPageController::class);

        Route::group(['prefix' => 'pages/{page}', 'as' => 'pages.'], function () {
            Route::post('/first', [AdminPageController::class, 'first'])->name('first');
            Route::post('/up', [AdminPageController::class, 'up'])->name('up');
            Route::post('/down', [AdminPageController::class, 'down'])->name('down');
            Route::post('/last', [AdminPageController::class, 'last'])->name('last');
        });

        Route::group(['prefix' => 'banners', 'as' => 'banners.'], function () {
            Route::get('/', [AdminBannerController::class, 'index'])->name('index');
            Route::get('/{banner}/show', [AdminBannerController::class, 'show'])->name('show');
            Route::put('/{banner}/edit', [AdminBannerController::class, 'edit']);
            Route::post('/{banner}/moderate', [AdminBannerController::class, 'moderate'])->name('moderate');
            Route::post('/{banner}/reject', [AdminBannerController::class, 'reject']);
            Route::post('/{banner}/pay', [AdminBannerController::class, 'pay'])->name('pay');
            Route::delete('/{banner}/destroy', [AdminBannerController::class, 'pay'])->name('destroy');
        });

        Route::group(['prefix' => 'tickets', 'as' => 'tickets.'], function () {
            Route::get('/', [AdminTicketController::class, 'index'])->name('index');
            Route::get('/{ticket}/show', [AdminTicketController::class, 'show'])->name('show');
            Route::put('/{ticket}/edit', [AdminTicketController::class, 'edit'])->name('edit');
            Route::post('{ticket}/message', [AdminTicketController::class, 'message'])->name('message');
            Route::post('/{ticket}/close', [AdminTicketController::class, 'close'])->name('close');
            Route::post('/{ticket}/approve', [AdminTicketController::class, 'approve'])->name('approve');
            Route::post('/{ticket}/reopen', [AdminTicketController::class, 'reopen'])->name('reopen');
            Route::delete('/{ticket}/destroy', [AdminTicketController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
            Route::get('{group}', [SettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
            Route::put('/settings/user', [SettingsController::class, 'updateUser'])->name('settings.user');
        });


        Route::get('logs', [LogViewerController::class, 'index'])->name('logs');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs');

    });

});
require __DIR__.'/auth.php';
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/contact', [StaticController::class, 'contact'])->name('contact');
Route::get('/page/{page_path}', [PageController::class, 'show'])->name('page')->where('page_path', '.+');
Route::get('/list/{urlPath?}', [IndexController::class, 'searchAdvert'])
    ->where('urlPath', '[a-z0-9-\/]+')->name('list.advert');
Route::get('/{urlPath?}', [IndexController::class, 'searchAdvert'])
    ->where('urlPath', '[a-z0-9-\/]+')->name('search.advert');
