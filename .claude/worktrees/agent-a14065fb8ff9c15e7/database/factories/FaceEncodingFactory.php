<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FaceEncoding;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaceEncodingFactory extends Factory
{
    #[\Override]
    protected $model = FaceEncoding::class;

    public function definition(): array
    {
        return [
            'encoding' => base64_encode(random_bytes(128)),
            'provider' => 'mock',
        ];
    }
}
