<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Source;
use App\Models\SourceRef;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SourceRef>
 */
class SourceRefFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = SourceRef::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // A person-level reference: (group, gid) is the pseudo-polymorphic key,
            // so gid only means people.id while group is 'indi'.
            'group' => SourceRef::GROUP_INDI,
            'gid' => Person::factory(),
            'sour_id' => Source::factory(),
            'text' => fake()->sentence(),
            'quay' => (string) fake()->numberBetween(0, 3),
            'page' => fake()->word(),
        ];
    }
}
