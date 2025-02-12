<?php

namespace Tests\Unit\Services;

use App\Http\Services\LocationService;
use App\Models\LocatedCountry;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class LocationServiceTest extends TestCase
{
    public function returns_all_countries()
    {
        $countries = collect([
            new LocatedCountry(['id' => 1, 'country' => 'Country 1']),
            new LocatedCountry(['id' => 2, 'country' => 'Country 2']),
        ]);

        // Mock the LocatedCountry model
        LocatedCountry::shouldReceive('select')
            ->with('id', 'country')
            ->once()
            ->andReturnSelf();
        LocatedCountry::shouldReceive('get')
            ->once()
            ->andReturn($countries);

        $locationService = new LocationService();
        $result = $locationService->getCountries();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals('Country 1', $result->first()->country);
    }
}
