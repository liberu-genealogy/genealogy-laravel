<?php

namespace Database\Factories;

use App\Models\Addr;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'adr1' => Str::limit($this->faker->address, 30),
            'adr2' => Str::limit($this->faker->address, 30),
            'city' => $this->faker->city,
            'stae' => $this->faker->state,
            'post' => $this->faker->postcode,
            'ctry' => $this->faker->countryCode,
        ];
    }
}
