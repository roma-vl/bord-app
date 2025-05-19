<?php

namespace App\Console\Commands\helpScripts;

use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLocationsToNestedSets extends Command
{
    protected $signature = 'locations:migrate-nested-sets';

    protected $description = 'Migrate existing locations into a nested sets structure';

    public function handle()
    {
        DB::transaction(function () {
            Location::truncate();

            $this->info('Processing countries...');
            $countries = DB::table('located_countrys')->get();
            foreach ($countries as $country) {
                $countryNode = Location::create([
                    'name' => $country->country,
                    'slug' => Location::generateSlug($country->country),
                    'depth' => 0,
                ]);

                $this->info("Processing regions for {$country->country}...");
                $regions = DB::table('located_region')->where('country', $country->id)->get();
                foreach ($regions as $region) {
                    $regionNode = $countryNode->children()->create([
                        'name' => $region->region,
                        'slug' => Location::generateSlug($region->region, $country->id),
                        'depth' => 1,
                    ]);

                    $this->info("Processing areas for {$region->region}...");
                    $areas = DB::table('located_area')->where('region', $region->id)->get();
                    foreach ($areas as $area) {
                        $areaNode = $regionNode->children()->create([
                            'name' => $area->area,
                            'slug' => Location::generateSlug($area->area, $region->id),
                            'depth' => 2,
                        ]);

                        $this->info("Processing villages for {$area->area}...");
                        $villages = DB::table('located_village')->where('area', $area->id)->get();
                        foreach ($villages as $village) {
                            $areaNode->children()->create([
                                'name' => $village->village,
                                'slug' => Location::generateSlug($village->village, $area->id),
                                'depth' => 3,
                            ]);
                        }
                    }
                }
            }
        });

        $this->info('Migration completed successfully.');
    }
}
