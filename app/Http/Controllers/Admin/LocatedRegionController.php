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
class LocatedRegionController extends Controller
{
    public function index(): Response
    {
        $regions = LocatedRegion::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/LocatedRegion/Index', ['regions' => $regions]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country' => 'required|exists:located_countrys,id',
            'region' => 'required|string|unique:located_region,region',
            'slug' => 'nullable|string|unique:located_region,slug',
        ]);

        LocatedRegion::create($validated);
        return redirect()->route('admin.located-regions.index')->with('success', 'Region created successfully');
    }

    public function update(Request $request, LocatedRegion $region): RedirectResponse
    {
        $validated = $request->validate([
            'country' => 'required|exists:located_countrys,id',
            'region' => 'required|string|unique:located_region,region,' . $region->id,
            'slug' => 'nullable|string|unique:located_region,slug,' . $region->id,
        ]);

        $region->update($validated);
        return redirect()->route('admin.located-regions.index')->with('success', 'Region updated successfully');
    }

    public function destroy(LocatedRegion $region): RedirectResponse
    {
        $region->delete();
        return redirect()->route('admin.located-regions.index')->with('info', 'Region deleted successfully');
    }
}
