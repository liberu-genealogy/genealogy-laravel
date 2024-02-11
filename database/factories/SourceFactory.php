<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Publication;
use App\Models\Repository;
use App\Models\Source;
// use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Source::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sour' => $this->faker->word(),
            'titl' => $this->faker->word(),
            'auth' => $this->faker->name(),
            'data' => $this->faker->word(),
            'date' => $this->faker->date(),
            'text' => $this->faker->word(),
            'publ' => $this->faker->text(),
            'abbr' => $this->faker->word(),
            'name' => $this->faker->word(),
            'description' => $this->faker->word(),
            'repository_id' => Repository::create()->id,
            'author_id' => Author::create([
                'description' => $this->faker->text(50),
                'is_active' => $this->faker->randomDigit('0', '1'),
                'name' => $this->faker->word(),
            ])->id,
            'publication_id' => Publication::create([
                'description' => $this->faker->text(50),
                'is_active' => $this->faker->randomDigit('0', '1'),
                'name' => $this->faker->word(),
            ])->id,
            //              'type_id' => Type::create([
            //                  'name' => $this->faker->name(),
            //                  'description' => $this->faker->text(50),
            //                 'is_active' => 1,
            //              ])->id,
            'is_active' => $this->faker->randomDigit('0', '1'),
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomDigit(),
            'quay' => $this->faker->word(),
            'page' => $this->faker->word(),
        ];
    }
}
