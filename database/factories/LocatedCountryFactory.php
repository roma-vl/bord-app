<?php
namespace Database\Factories;

use App\Models\LocatedCountry;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocatedCountryFactory extends Factory
{
    protected $model = LocatedCountry::class;

    public function definition()
    {
        return [
            'country' => $this->faker->country,
            'slug' => $this->faker->slug,
        ];
    }
}

