<?php

namespace Tests\Unit\Services;

use App\Http\Services\LocationService;
use App\Models\LocatedCountry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LocationService $locationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->locationService = new LocationService;
    }

    public function test_returns_list_of_countries()
    {
        LocatedCountry::factory()->create();

        $countries = $this->locationService->getCountries();

        $this->assertCount(2, $countries->toArray());
    }

    public function test_returns_list_of_regions_for_given_country()
    {
        $country = LocatedCountry::factory()->create();
        $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region',
        ]);

        $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region2',
        ]);

        $regions = $this->locationService->getRegions($country->id);

        $this->assertCount(2, $regions);
    }

    public function test_returns_list_of_areas_for_given_region()
    {
        $country = LocatedCountry::factory()->create();
        $region = $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region',
        ]);

        $this->locationService->createLocation([
            'type' => 'area',
            'country_id' => $country->id,
            'region_id' => $region->id,
            'name' => 'Obukhi',
        ]);

        $this->locationService->createLocation([
            'type' => 'area',
            'country_id' => $country->id,
            'region_id' => $region->id,
            'name' => 'Obukhi2',
        ]);

        $areas = $this->locationService->getAreas($region->id);

        $this->assertCount(2, $areas);
    }

    public function test_creates_a_new_country()
    {
        LocatedCountry::factory()->create();
        $country = $this->locationService->createLocation([
            'type' => 'country',
            'name' => 'Switzerland',
        ]);

        $this->assertDatabaseHas('located_countrys', ['id' => $country->id, 'country' => 'Switzerland']);
    }

    public function test_creates_a_new_region()
    {
        $country = LocatedCountry::factory()->create();
        $this->locationService->createLocation([
            'type' => 'country',
            'name' => 'Ukraine1',
        ]);

        $region = $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region',
        ]);

        $this->assertDatabaseHas('located_region', ['id' => $region->id, 'region' => 'Kyiv Region']);
    }

    public function test_creates_a_new_area()
    {
        $country = LocatedCountry::factory()->create();
        $this->locationService->createLocation([
            'type' => 'country',
            'name' => 'Ukraine1',
        ]);

        $region = $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region',
        ]);

        $area = $this->locationService->createLocation([
            'type' => 'area',
            'country_id' => $country->id,
            'region_id' => $region->id,
            'name' => 'Obukhiv',
        ]);

        $this->assertDatabaseHas('located_area', ['id' => $area->id, 'area' => 'Obukhiv']);
    }

    public function test_creates_a_new_village()
    {
        $country = LocatedCountry::factory()->create();
        $this->locationService->createLocation([
            'type' => 'country',
            'name' => 'Ukraine1',
        ]);

        $region = $this->locationService->createLocation([
            'type' => 'region',
            'country_id' => $country->id,
            'name' => 'Kyiv Region',
        ]);

        $area = $this->locationService->createLocation([
            'type' => 'area',
            'country_id' => $country->id,
            'region_id' => $region->id,
            'name' => 'Obuk',
        ]);

        $village = $this->locationService->createLocation([
            'type' => 'village',
            'country_id' => $country->id,
            'region_id' => $region->id,
            'area_id' => $area->id,
            'name' => 'Berezivka',
        ]);

        $this->assertDatabaseHas('located_village', ['id' => $village->id, 'village' => 'Berezivka']);
    }

    public function test_fails_when_creating_location_with_invalid_type()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->locationService->createLocation(['type' => 'invalid_type', 'name' => 'Test']);
    }

    public function test_deletes_location_by_type_and_id()
    {
        $country = LocatedCountry::factory()->create();

        $this->assertDatabaseHas('located_countrys', ['id' => $country->id]);

        $this->locationService->deleteLocation($country->id, 'country');

        $this->assertDatabaseMissing('located_countrys', ['id' => $country->id]);
    }
}
