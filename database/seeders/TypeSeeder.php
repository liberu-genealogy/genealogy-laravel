<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Seed a small set of repository types so the Repository form's
     * "type" select has real rows to offer.
     *
     * ponytail: `types` is a flat shared lookup (no discriminator column),
     * also used by Family/Note. These generic values are harmless there.
     * Rows are seeded with a null team_id (seeder runs without auth); under
     * tenancy the Type select is team-scoped, so surface them per-team if the
     * app needs them visible inside a tenant.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Archive', 'description' => 'Public or private archive holding original records'],
            ['name' => 'Library', 'description' => 'Library holding books, periodicals and reference material'],
            ['name' => 'Museum', 'description' => 'Museum or heritage collection'],
            ['name' => 'Website', 'description' => 'Online repository or digital record collection'],
            ['name' => 'Personal Collection', 'description' => 'Privately held family papers and artefacts'],
            ['name' => 'Government Office', 'description' => 'Civil registration or government records office'],
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description'], 'is_active' => true],
            );
        }
    }
}
