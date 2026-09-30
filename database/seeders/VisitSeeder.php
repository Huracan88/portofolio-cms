<?php

namespace Database\Seeders;

use App\Models\Visit;
use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        if (Visit::count() > 0) {
            return;
        }

        // Seed realistic traffic for demo and testing
        Visit::factory()->count(60)->create();
    }
}
