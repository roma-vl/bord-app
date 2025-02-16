<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;


class AdvertController extends Controller
{
    public function index(): Response    {
        return Inertia::render('Account/Advert/Index');
    }

}
