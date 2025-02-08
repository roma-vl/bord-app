<?php
namespace App\Http\Controllers\Admin;

use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedArea;
use App\Models\LocatedVillage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        $this->validateLocation($request);

        $this->createLocation($request);

        return redirect()->back();
    }

    public function show($type, $id): JsonResponse
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

    protected function deleteLocation($id, $type)
    {
        $model = $this->getModel($type);
        if (!$model) {
            return response()->json(['message' => 'Тип локації не знайдено'], 400);
        }

        $item = $model::findOrFail($id);
        $item->delete();
    }

    public function destroy($id, Request $request): RedirectResponse
    {
        $this->deleteLocation($id, $request->type);

        return redirect()->back();
    }

    protected function createLocation(Request $request)
    {
        $model = $this->getModel($request->type);
        if (!$model) {
            return response()->json(['message' => 'Тип локації не знайдено'], 400);
        }

        $data = [
            'slug' => \Str::slug($request->name),
        ];

        switch ($request->type) {
            case 'country':
                $data['country'] = $request->name;
                break;
            case 'region':
                $data['country'] = $request->country_id;
                $data['region'] = $request->name;
                break;
            case 'area':
                $data['country'] = $request->country_id;
                $data['region'] = $request->region_id;
                $data['area'] = $request->name;
                break;
            case 'village':
                $data['country'] = $request->country_id;
                $data['region'] = $request->region_id;
                $data['area'] = $request->area_id;
                $data['village'] = $request->name;
                break;
        }

        return $model::create($data);
    }

    protected function validateLocation(Request $request): void
    {
        $rules = [
            'type' => 'required|in:country,region,area,village',
            'name' => 'required|string|max:150',
        ];

        if ($request->type === 'region') {
            $rules['country_id'] = 'required|integer';
        } elseif ($request->type === 'area') {
            $rules['country_id'] = 'required|integer';
            $rules['region_id'] = 'required|integer';
        } elseif ($request->type === 'village') {
            $rules['country_id'] = 'required|integer';
            $rules['region_id'] = 'required|integer';
            $rules['area_id'] = 'required|integer';
        }

        $request->validate($rules);
    }


    protected function getModel($type): ?string
    {
        return match ($type) {
            'country' => LocatedCountry::class,
            'region' => LocatedRegion::class,
            'area' => LocatedArea::class,
            'village' => LocatedVillage::class,
            default => null,
        };
    }
}
