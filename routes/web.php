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
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index', [
        'categories' => [
            ['id' => 1, 'name' => 'Оренда', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png'],
            ['id' => 2, 'name' => 'Дитячий світ', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/detskiy-mir-36-1x.png'],
            ['id' => 3, 'name' => 'Нерухомість', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/nedvizhimost-1-1x.png'],
            ['id' => 4, 'name' => 'Авто', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/transport-1532-1x.png'],
            ['id' => 5, 'name' => 'Запчастини для транспорту', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/zapchasti-dlya-transporta-3-1x.png'],
            ['id' => 6, 'name' => 'Робота', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/rabota-6-1x.png'],
            ['id' => 7, 'name' => 'Оренда', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png'],
            ['id' => 8, 'name' => 'Дитячий світ', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/detskiy-mir-36-1x.png'],
            ['id' => 9, 'name' => 'Нерухомість', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/nedvizhimost-1-1x.png'],
            ['id' => 10, 'name' => 'Авто', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/transport-1532-1x.png'],
            ['id' => 11, 'name' => 'Запчастини для транспорту', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/zapchasti-dlya-transporta-3-1x.png'],
            ['id' => 12, 'name' => 'Робота', 'icon' => 'https://categories.olxcdn.com/assets/categories/olxua/rabota-6-1x.png'],
        ],
        'listings' => [
            ['id' => 1, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 2, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 3, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 4, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 5, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 6, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 7, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 8, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
        ],
        'news' => [
            ['id' => 1, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 2, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 3, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 4, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
        ],
    ]);
})
->name('main');


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
