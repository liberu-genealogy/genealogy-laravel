<?php

namespace Database\Factories;

use App\Models\FaceEncoding;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaceEncodingFactory extends Factory
{
    protected $model = FaceEncoding::class;

    public function definition(): array
    {
        return [
            'encoding' => base64_encode(random_bytes(128)),
            'provider' => 'mock',
        ];
    }
}
