<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Publication;
use App\Models\Repository;
use App\Models\Source;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
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
            'sour'          => fake()->word(),
            'titl'          => fake()->word(),
            'auth'          => fake()->name(),
            'data'          => fake()->word(),
            'date'          => fake()->date(),
            'text'          => fake()->word(),
            'publ'          => fake()->text(),
            'abbr'          => fake()->word(),
            'name'          => fake()->word(),
            'description'   => fake()->word(),
            'repository_id' => Repository::create()->id,
            'author_id'     => Author::create([
                'description' => fake()->text(50),
                'is_active'   => fake()->randomDigit(),
                'name'        => fake()->word(),
            ])->id,
            'publication_id' => Publication::create([
                'description' => fake()->text(50),
                'is_active'   => fake()->randomDigit(),
                'name'        => fake()->word(),
            ])->id,
            //              'type_id' => Type::create([
            //                  'name' => $this->faker->name(),
            //                  'description' => $this->faker->text(50),
            //                 'is_active' => 1,
            //              ])->id,
            'is_active' => fake()->randomDigit(),
            'group'     => fake()->word(),
            'gid'       => fake()->randomDigit(),
            'quay'      => fake()->word(),
            'page'      => fake()->word(),
        ];
    }
}
