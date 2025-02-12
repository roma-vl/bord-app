<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LocationRequest;
use App\Http\Services\LocationService;
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
        return response()->json($this->locationService->getCountries());
    }

    public function getRegions(int $countryId): JsonResponse
    {
        return response()->json($this->locationService->getRegions($countryId));
    }

    public function getAreas(int $regionId): JsonResponse
    {
        return response()->json($this->locationService->getAreas($regionId));
    }

    public function getVillages(int $areaId): JsonResponse
    {
        return response()->json($this->locationService->getVillages($areaId));
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
