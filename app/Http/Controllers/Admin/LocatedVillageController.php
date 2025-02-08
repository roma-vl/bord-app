<?php

namespace App\Http\Controllers\Admin;

use App\Models\LocatedVillage;
use Inertia\Inertia;
use Inertia\Response;

class LocatedVillageController extends Controller
{
    public function index(): Response
    {
        $villages = LocatedVillage::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/LocatedVillage/Index', compact('villages'));
    }
}
