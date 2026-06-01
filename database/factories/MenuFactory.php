<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    #[\Override]
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'name' => fake()->word,
            'url' => fake()->url,
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
