<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function changeLocale(string $locale): RedirectResponse
    {
        if (!in_array($locale, ['en', 'uk'])) {
            abort(400);
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        session()->flash('success', trans('auth.language'));

        return redirect()->back();
    }

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    public function index(): Response
    {
        $categories = [
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
        ];

        $listings =  [
            ['id' => 1, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 2, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 3, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 4, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 5, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 6, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 7, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 8, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
        ];

        $news =[
            ['id' => 1, 'title' => 'Набір шампурів', 'price' => '2 300 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 2, 'title' => 'Помещение 550м2', 'price' => '80 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 3, 'title' => 'Холодильник Bosch', 'price' => '6 000 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
            ['id' => 4, 'title' => 'Кровать DeSon', 'price' => '4 100 грн', 'image' => 'https://ireland.apollo.olxcdn.com/v1/files/hgml8iew7lil3-UA/image;s=1000x700'],
        ];

        $regions = [
            ['id' => 1, 'region' => 'Київська область',],
            ['id' => 2, 'region' => 'Львівська область'],
            ['id' => 3, 'region' => 'Одеська область'],
            ['id' => 4, 'region' => 'Харківська область'],
            ['id' => 5, 'region' => 'Дніпропетровська область'],
            ['id' => 6, 'region' => 'Запорізька область'],

        ];
        return Inertia::render('Index', compact('categories', 'listings', 'news', 'regions'));
    }
}
