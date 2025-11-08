<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beneficiary;
use Illuminate\Support\Str;

class BeneficiarySeeder extends Seeder
{
    public function run(): void
    {
        // Tworzenie systemowego beneficjenta
        Beneficiary::updateOrCreate(
            ['email' => 'system@feer.org.pl'],
            [
                'first_name' => 'Systemowy',
                'last_name' => 'Beneficjent',
                'phone' => '000000000',
                'class_link' => 'https://zoom.us/system',
                'active' => 1,
                'slug' => 'system-beneficjent',
            ]
        );

        // Przykładowi normalni beneficjenci
        $beneficiaries = [
            [
                'first_name' => 'Piotr',
                'last_name' => 'Wiśniewski',
                'phone' => '555666777',
                'email' => 'piotr.wisniewski@example.com',
                'class_link' => 'https://zoom.us/j/5556667770',
            ],
            [
                'first_name' => 'Anna',
                'last_name' => 'Kowalska',
                'phone' => '555888999',
                'email' => 'anna.kowalska@example.com',
                'class_link' => 'https://zoom.us/j/5558889990',
            ],
        ];

        foreach ($beneficiaries as $b) {
            Beneficiary::updateOrCreate(
                ['email' => $b['email']],
                [
                    'first_name' => $b['first_name'],
                    'last_name' => $b['last_name'],
                    'phone' => $b['phone'],
                    'class_link' => $b['class_link'],
                    'active' => 1,
                    // Generujemy slug bez polskich znaków
                    'slug' => Str::slug($b['first_name'] . ' ' . $b['last_name']),
                ]
            );
        }
    }
}
