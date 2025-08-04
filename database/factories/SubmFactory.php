<?php

namespace Database\Factories;

use App\Models\Addr;
use App\Models\Subm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subm>
 */
class SubmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'      => fake()->word(),
            'gid'        => fake()->randomDigit(),
            'name'       => fake()->word(),
            'addr_id'    => Addr::create()->id,
            'rin'        => fake()->word(),
            'rfn'        => fake()->word(),
            'lang'       => fake()->languageCode(),
            'phon'       => fake()->phoneNumber(),
            'email'      => fake()->email(),
            'fax'        => fake()->word(),
            'www'        => fake()->url(),
            'created_at' => fake()->date(),
        ];
    }
}
