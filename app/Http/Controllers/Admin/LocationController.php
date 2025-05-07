<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LocationRequest;
use App\Http\Services\LocationService;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Locations/Index');
    }

    public function getCountries(): JsonResponse
    {
        $ff =$this->locationService->getCountries();
        return response()->json($this->locationService->getCountries());
    }

    public function getRegions(int $countryId): JsonResponse
    {
        $country = Location::findOrFail($countryId);
        return response()->json($this->locationService->getRegions($country));
    }

    public function getAreas(int $regionId): JsonResponse
    {
        $region = Location::findOrFail($regionId);
        return response()->json($this->locationService->getAreas($region));
    }

    public function getVillages(int $areaId): JsonResponse
    {
        $area = Location::findOrFail($areaId);
        return response()->json($this->locationService->getVillages($area));
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        $this->locationService->createLocation($request->validated());
        return redirect()->back()->with('success', 'Локацію створено');
    }

    public function show(string $type, int $id): JsonResponse
    {
        $data = $this->locationService->getLocation($type, $id);
        return response()->json($data);
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $this->locationService->deleteLocation( $id, $type);
        return redirect()->back()->with('info', "Локацію {$type} видалено");
    }
}
