<?php

namespace Database\Factories;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumPost>
 */
class ForumPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ForumPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'topic_id' => ForumTopic::factory(), 'content' => fake()->text(), 'author' => fake()->name(),
        ];
    }
}
