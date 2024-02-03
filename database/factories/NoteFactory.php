<?php

namespace Database\Factories;

use App\Models\Note;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomDigit('1', '2'),
            'note' => $this->faker->text(),
            'rin' => $this->faker->word(),
            'name' => $this->faker->word(),
            'date' => $this->faker->date(),
            'description' => $this->faker->text(50),
            'is_active' => $this->faker->randomDigit('0', '1'),
            //            'type_id' => Type::create(['name' => $this->faker->name, 'description' => $this->faker->text])->id,
        ];
    }
}
