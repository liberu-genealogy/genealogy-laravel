<?php

namespace Database\Factories;

use App\Models\Addr;
use App\Models\Repository;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'repo'    => $this->faker->word(),
            'name'    => $this->faker->word(),
            'addr_id' => Addr::create([
                'adr1' => $this->faker->address(30),
                'adr2' => $this->faker->address(30),
                'city' => $this->faker->city(),
                'stae' => $this->faker->state(),
                'post' => $this->faker->postcode(),
                'ctry' => $this->faker->countryCode(),
            ])->id,
            'date'        => $this->faker->date(),
            'rin'         => $this->faker->word(),
            'phon'        => $this->faker->phoneNumber(),
            'email'       => $this->faker->email(),
            'fax'         => $this->faker->phoneNumber(),
            'www'         => $this->faker->url(),
            'name'        => $this->faker->name(),
            'description' => $this->faker->text(50),
            //  'type_id' => Type::create([
            //     'name' => $this->faker->word(),
            //      'description' => $this->faker->text(50),
            //      'is_active' => $this->faker->randomDigit('0', '1'),
            //   ])->id,
            'is_active' => $this->faker->randomDigit('0', '1'),
        ];
    }
}
