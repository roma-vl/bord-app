<?php

namespace App\Http\Services;

use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedArea;
use App\Models\LocatedVillage;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class LocationService
{
    public function getCountries(): Collection
    {
        return LocatedCountry::select('id', 'country')->get();
    }

    public function getRegions(int $countryId): Collection
    {
        return LocatedRegion::where('country', $countryId)->select('id', 'region')->get();
    }

    public function getAreas(int $regionId): Collection
    {
        return LocatedArea::where('region', $regionId)->select('id', 'area')->get();
    }

    public function getVillages(int $areaId): Collection
    {
        return LocatedVillage::where('area', $areaId)->select('id', 'village')->get();
    }

    public function getLocation(string $type, int $id): ?Model
    {
        $model = $this->getModel($type);
        return $model ? $model::findOrFail($id) : null;
    }

    public function createLocation(array $data): Model
    {
        $model = $this->getModel($data['type']);

        if (!$model) {
            throw new \InvalidArgumentException("Невірний тип локації");
        }

        $locationData = ['slug' => \Str::slug($data['name'])];

        match ($data['type']) {
            'country' => $locationData['country'] = $data['name'],
            'region'  => $locationData = [...$locationData, 'country' => $data['country_id'], 'region' => $data['name']],
            'area'    => $locationData = [...$locationData, 'country' => $data['country_id'], 'region' => $data['region_id'], 'area' => $data['name']],
            'village' => $locationData = [...$locationData, 'country' => $data['country_id'], 'region' => $data['region_id'], 'area' => $data['area_id'], 'village' => $data['name']],
        };

        return $model::create($locationData);
    }

    public function deleteLocation(int $id, string $type): void
    {
        $model = $this->getModel($type);
        if ($model) {
            $location = $model::findOrFail($id);
            $location->delete();
        }
    }

    private function getModel(string $type): ?string
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
