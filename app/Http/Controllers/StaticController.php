<?php

namespace App\Http\Controllers;

use App\Http\Services\Adverts\AdvertService;
use App\Http\Services\CategoryService;
use App\Models\Adverts\Advert;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class StaticController extends Controller
{
    public function contact(): Response
    {
        return Inertia::render('Static/Contact');
    }


}
