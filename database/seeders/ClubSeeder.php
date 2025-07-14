<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\SchoolYear;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = Club::create([
            'name' => 'Homemakers',
            'description' => 'Description for Club Homemakers',
            'status' => 'active',
        ]);
        $club->clubRegister()->create([
            'club_id' => $club->id,
            'user_id' => 2,
            'school_year_id' => SchoolYear::current()->id,
            'status' => 'active',
        ]);
        for($i = 1; $i <= 20; $i++) {
            $club = Club::create([
                'name' => 'Club ' . $i,
                'description' => 'Description for Club ' . $i,
                'status' => 'active',
            ]);
            $club->clubRegister()->create([
                'club_id' => $club->id,
                'user_id' => 2 + $i,
                'school_year_id' => SchoolYear::current()->id,
                'status' => 'active',
            ]);
        }
    }
}
