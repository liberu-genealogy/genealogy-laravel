<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Type;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'       => fake()->word(),
            'gid'         => fake()->randomDigit(),
            'note'        => fake()->text(),
            'rin'         => fake()->word(),
            'name'        => fake()->word(),
            'date'        => fake()->date(),
            'description' => fake()->text(50),
            'is_active'   => fake()->randomDigit(),
            'type_id'     => Type::where('id', fake()->randomElement([1, 2, 3, 4]))->first()->id,
        ];
    }
}
