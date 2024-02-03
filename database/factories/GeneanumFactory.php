<?php

namespace Database\Factories;

use App\Models\Geneanum;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneanumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Geneanum::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'remote_id',
            'data',
            'area',
            'db_name',
        ];
    }
}
