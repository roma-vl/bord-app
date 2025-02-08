<?php
namespace App\Http\Controllers\Admin;

use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedArea;
use App\Models\LocatedVillage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Locations/Index');
    }

    public function getCountries()
    {
        return response()->json(LocatedCountry::select('id', 'country')->get());
    }

    public function getRegions($countryId)
    {
        return response()->json(
            LocatedRegion::where('country', $countryId)->select('id', 'region')->get()
        );
    }

    public function getAreas($regionId)
    {
        return response()->json(
            LocatedArea::where('region', $regionId)->select('id', 'area')->get()
        );
    }

    public function getVillages($areaId)
    {
        return response()->json(
            LocatedVillage::where('area', $areaId)->select('id', 'village')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:country,region,area,village',
            'name' => 'required|string|max:150',
            'country' => 'nullable|integer',
            'region' => 'nullable|integer',
            'area' => 'nullable|integer',
            'village' => 'nullable|integer',
        ]);

        switch ($request->type) {
            case 'country':
                LocatedCountry::create([
                    'country' => $request->name,
                    'slug' => \Str::slug($request->name)]);
                break;
            case 'region':
                LocatedRegion::create([
                    'country' => $request->country_id,
                    'region' => $request->name,
                    'slug' => \Str::slug($request->name)]);
                break;
            case 'area':
                LocatedArea::create([
                    'country' => $request->country_id,
                    'region' => $request->region_id,
                    'area' => $request->name,
                    'slug' => \Str::slug($request->name)
                ]);
                break;
            case 'village':
                LocatedVillage::create([
                    'country' => $request->country_id,
                    'region' => $request->region_id,
                    'area' => $request->area_id,
                    'village' => $request->name,
                    'slug' => \Str::slug($request->name)]);
                break;
        }

        return redirect()->back();
    }

    public function show($type, $id)
    {
        $model = match ($type) {
            'country' => LocatedCountry::class,
            'region' => LocatedRegion::class,
            'area' => LocatedArea::class,
            'village' => LocatedVillage::class,
            default => null,
        };

        if (!$model) {
            return response()->json(['message' => 'Тип не знайдено'], 404);
        }

        $data = $model::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id, Request $request)
    {
        switch ($request->type) {
            case 'country':
                LocatedCountry::findOrFail($id)->delete();
                break;
            case 'region':
                LocatedRegion::findOrFail($id)->delete();
                break;
            case 'area':
                LocatedArea::findOrFail($id)->delete();
                break;
            case 'village':
                LocatedVillage::findOrFail($id)->delete();
                break;
        }

        return redirect()->back();
    }
}
