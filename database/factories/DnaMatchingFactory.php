<?php

namespace Database\Factories;

use App\Models\DnaMatching;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DnaMatching>
 */
class DnaMatchingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DnaMatching::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'match_id' => \App\Models\User::factory(),
            'match_name' => $this->faker->name,
            'file1' => 'dna-test-files/file1.txt',
            'file2' => 'dna-test-files/file2.txt',
            'image' => 'dna-test-files/match.png',
            'total_shared_cm' => $this->faker->randomFloat(2, 20, 500),
            'largest_cm_segment' => $this->faker->randomFloat(2, 5, 100),
            'confidence_level' => $this->faker->numberBetween(40, 99),
            'predicted_relationship' => $this->faker->randomElement([
                'First Cousin',
                'Second Cousin',
                'Third Cousin',
                'Distant Cousin',
            ]),
            'shared_segments_count' => $this->faker->numberBetween(5, 50),
            'match_quality_score' => $this->faker->randomFloat(2, 30, 100),
            'analysis_date' => now(),
        ];
    }
}
