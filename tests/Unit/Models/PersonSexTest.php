<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Person;
use Tests\TestCase;

class PersonSexTest extends TestCase
{
    public function test_get_sex_maps_male(): void
    {
        $this->assertSame('Male', (new Person(['sex' => 'M']))->getSex());
    }

    public function test_get_sex_maps_female(): void
    {
        // Bites the old bug: buggy getSex() returned 'Male' for 'F'.
        $this->assertSame('Female', (new Person(['sex' => 'F']))->getSex());
    }

    public function test_get_sex_maps_unknown_code(): void
    {
        $this->assertSame('Unknown', (new Person(['sex' => 'U']))->getSex());
    }

    public function test_get_sex_defaults_unknown_when_unset(): void
    {
        // Bites the old bug: buggy getSex() returned 'Male' for null/unset.
        $this->assertSame('Unknown', (new Person)->getSex());
    }
}
