<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@feer.org.pl'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin'),
                'is_admin' => true,
            ]
        );

        $this->command->info("Utworzono lub zaktualizowano użytkownika admin@feer.org.pl z hasłem 'admin'.");
    }
}
