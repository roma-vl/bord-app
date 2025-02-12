<?php

namespace Database\Factories;

use App\Models\LocatedRegion;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocatedRegionFactory extends Factory
{
    protected $model = LocatedRegion::class;

    public function definition()
    {
        return [
            'country' => $this->faker->country,
            'region' => $this->faker->state,
            'slug' => $this->faker->slug('3'),
        ];
    }
}
