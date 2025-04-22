<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(20),
            // 'folder' => 'null',
            // 'model' => $this->faker->text(20),
            // 'icon' => 'folder',
            // 'endpoint' => null,
            'description' => $this->faker->text(),
            // 'is_browsable' => false,
            // 'is_system' => false,
            'is_active' => $this->faker->boolean(),
        ];
    }

    public function model(string $model): self
    {
        $name = Str::of($model)->afterLast('\\')
            ->snake()
            ->replace('_', ' ')
            ->title()
            ->plural();

        return $this->state(fn () => [
            'name'        => $name,
            'folder'      => $name->camel(),
            'model'       => $model,
            'description' => "Liberu {$name}",
        ]);
    }
}
