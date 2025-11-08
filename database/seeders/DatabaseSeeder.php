<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BeneficiarySeeder::class,
            AdminUserSeeder::class,
            // LicensesSeeder::class, // odpal jeśli już masz licencje
        ]);
    }
}
