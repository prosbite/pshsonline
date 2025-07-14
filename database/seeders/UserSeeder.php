<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Gret',
            'email' => 'gret@admin.com',
            'password' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);
        for($i = 1; $i <= 20; $i++) {
            \App\Models\User::factory()->create([
                'name' => 'Club Adviser ' . $i,
                'email' => 'club_adviser' . $i . '@clubadviser.com',
                'password' => 'club_adviser_' . $i,
                'role' => 'club adviser',
                'status' => 'active',
            ]);
        }
    }
}
