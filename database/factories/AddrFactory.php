<?php

namespace Database\Factories;

use App\Models\Addr;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addr>
 */
class AddrFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Addr::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'adr1' => Str::limit(fake()->address, 30),
            'adr2' => Str::limit(fake()->address, 30),
            'city' => fake()->city,
            'stae' => fake()->state,
            'post' => fake()->postcode,
            'ctry' => fake()->countryCode,
        ];
    }
}
