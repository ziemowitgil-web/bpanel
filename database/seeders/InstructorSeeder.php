<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::updateOrCreate(
            ['email' => 'ziemowit.gil@feer.org.pl'],
            [
                'first_name' => 'Ziemowit',
                'last_name' => 'Gil',
                'phone' => '535231923',
            ]
        );
    }
}
