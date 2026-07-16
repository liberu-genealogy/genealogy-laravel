<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dna>
 */
class DnaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Dna::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'file_name' => 'dna-test-files/' . $this->faker->uuid . '.txt',
            'variable_name' => 'var_' . $this->faker->unique()->bothify('?????'),
            'user_id' => \App\Models\User::factory(),
            // Factory kits consent by default so matching/notification tests run;
            // real uploads must tick the consent box (enforced in DnaResource).
            'consent_given' => true,
            'consent_given_at' => now(),
        ];
    }
}
