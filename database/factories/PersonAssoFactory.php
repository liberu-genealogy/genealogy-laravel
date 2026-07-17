<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AssociationType;
use App\Models\Person;
use App\Models\PersonAsso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonAsso>
 */
class PersonAssoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = PersonAsso::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => PersonAsso::GROUP_INDI,
            'gid' => Person::factory(),
            // `indi` is a varchar: a resolved row holds the person id as a string,
            // an unresolved import holds the raw GEDCOM xref ("@I5@") instead.
            'indi' => fn (): string => (string) Person::factory()->create()->getKey(),
            'rela' => fake()->randomElement(AssociationType::cases())->value,
            'import_confirm' => 1,
        ];
    }
}
