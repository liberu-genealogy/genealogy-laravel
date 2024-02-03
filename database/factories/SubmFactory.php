<?php

namespace Database\Factories;

use App\Models\Addr;
use App\Models\Subm;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'group' => $this->faker->word(),
            'gid' =>  $this->faker->randomDigit('1', '2'),
            'name' => $this->faker->word(),
            'addr_id' => Addr::create()->id,
            'rin' => $this->faker->word(),
            'rfn' => $this->faker->word(),
            'lang' => $this->faker->languageCode(),
            'phon' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'fax' => $this->faker->word(),
            'www' => $this->faker->url(),
            'created_at' => $this->faker->date(),
        ];
    }
}
