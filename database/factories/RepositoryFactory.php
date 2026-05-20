<?php

namespace Database\Factories;

use App\Models\Addr;
use App\Models\Repository;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repository>
 */
class RepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'repo'    => fake()->word(),
            'name'    => fake()->word(),
            'addr_id' => Addr::create([
                'adr1' => fake()->address(),
                'adr2' => fake()->address(),
                'city' => fake()->city(),
                'stae' => fake()->state(),
                'post' => fake()->postcode(),
                'ctry' => fake()->countryCode(),
            ])->id,
            'date'        => fake()->date(),
            'rin'         => fake()->word(),
            'phon'        => fake()->phoneNumber(),
            'email'       => fake()->email(),
            'fax'         => fake()->phoneNumber(),
            'www'         => fake()->url(),
            'name'        => fake()->name(),
            'description' => fake()->text(50),
            //  'type_id' => Type::create([
            //     'name' => $this->faker->word(),
            //      'description' => $this->faker->text(50),
            //      'is_active' => $this->faker->randomDigit('0', '1'),
            //   ])->id,
            'is_active' => fake()->randomDigit(),
        ];
    }
}
