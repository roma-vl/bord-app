<?php

namespace App\Http\Controllers\Admin;

use App\Models\LocatedArea;
use Inertia\Inertia;
use Inertia\Response;

class LocatedAreaController extends Controller
{
    public function index(): Response
    {
        $areas = LocatedArea::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/LocatedArea/Index', compact('areas'));
    }
}
