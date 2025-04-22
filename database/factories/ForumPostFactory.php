<?php

namespace Database\Factories;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'topic_id' => ForumTopic::factory(), 'content' => $this->faker->text(), 'author' => $this->faker->name(),
        ];
    }
}
