<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownSeeder extends Seeder
{
    public function run()
    {
        $this->seedCountries();
        // Add calls to other seed methods here
    }

    private function seedCountries()
    {
        DB::transaction(function () {
            DB::table('countries')->insert([
                ['name' => 'United States'],
                ['name' => 'Canada'],
                ['name' => 'Australia'],
                // Add more countries as needed
            ]);
        });
    }

    // Implement other seed methods similar to seedCountries() for different dropdowns
}
