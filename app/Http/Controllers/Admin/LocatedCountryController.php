<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedArea;
use App\Models\LocatedVillage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LocatedCountryController extends Controller
{
    public function index(): Response
    {
        $countries = LocatedCountry::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/LocatedCountry/Index', ['countries' => $countries]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country' => 'required|string|unique:located_countrys,country',
            'slug' => 'nullable|string|unique:located_countrys,slug',
        ]);

        LocatedCountry::create($validated);
        return redirect()->route('admin.located-countries.index')->with('success', 'Country created successfully');
    }

    public function update(Request $request, LocatedCountry $country): RedirectResponse
    {
        $validated = $request->validate([
            'country' => 'required|string|unique:located_countrys,country,' . $country->id,
            'slug' => 'nullable|string|unique:located_countrys,slug,' . $country->id,
        ]);

        $country->update($validated);
        return redirect()->route('admin.located-countries.index')->with('success', 'Country updated successfully');
    }

    public function destroy(LocatedCountry $country): RedirectResponse
    {
        $country->delete();
        return redirect()->route('admin.located-countries.index')->with('info', 'Country deleted successfully');
    }
}
