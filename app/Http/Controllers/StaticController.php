<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class StaticController extends Controller
{
    public function contact(): Response
    {
        return Inertia::render('Static/Contact');
    }
}
