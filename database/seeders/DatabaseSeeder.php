<?php

namespace Database\Seeders;

use App\Models\Transmission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Add all your seeders here
        $this->call([
            RolesAndPermissionsSeeder::class,

        ]);

        $this->command->info('✅ All seeders executed successfully!');
    }
}
