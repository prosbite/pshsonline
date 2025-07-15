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
        $clubs = [
            [
                'nature' => 'Student Organization 1',
                'name' => 'Sangguniang Iskolar',
                'description' => 'Student Organization 1',
                'adviser' => 2,
            ],
            [
                'nature' => 'Student Organization 2',
                'name' => 'The 13th Scholar',
                'description' => 'Student Organization 2',
                'adviser' => 3,
            ],
            [
                'nature' => 'Student Organization 3',
                'name' => 'Caraga Iskolar',
                'description' => 'Student Organization 3',
                'adviser' => 4,
            ],
            [
                'nature' => 'Student Organization 4',
                'name' => 'Model United Nations',
                'description' => 'Student Organization 4',
                'adviser' => 5,
            ],
            [
                'nature' => 'ALP 1',
                'name' => 'Basketball Club',
                'description' => 'Basketball Club',
                'adviser' => 6,
            ],
            [
                'nature' => 'ALP 2',
                'name' => 'Volleyball Club',
                'description' => 'Volleyball Club',
                'adviser' => 7,
            ],
            [
                'nature' => 'ALP 3',
                'name' => 'Badminton Club',
                'description' => 'Badminton Club',
                'adviser' => 8,
            ],
            [
                'nature' => 'ALP 4',
                'name' => 'Table Tennis Club',
                'description' => 'Table Tennis Club',
                'adviser' => 9,
            ],
            [
                'nature' => 'ALP 5',
                'name' => 'Debate Club',
                'description' => 'Debate Club',
                'adviser' => 10,
            ],
            [
                'nature' => 'ALP 6',
                'name' => 'Multimedia Club',
                'description' => 'Multimedia Club',
                'adviser' => 11,
            ],
            [
                'nature' => 'ALP 7',
                'name' => 'Homemakers Club',
                'description' => 'Homemakers Club',
                'adviser' => 12,
            ],
            [
                'nature' => 'ALP 8',
                'name' => 'Dibuho Club',
                'description' => 'Dibuho Club',
                'adviser' => 13,
            ],
            [
                'nature' => 'ALP 9',
                'name' => 'Robotics Enthusiasts',
                'description' => 'Robotics Enthusiasts',
                'adviser' => 14,
            ],
            [
                'nature' => 'ALP 10',
                'name' => 'Sigalab',
                'description' => 'Performing Arts Club (acting, dance, physical theatre, others)',
                'adviser' => 15,
            ],
            [
                'nature' => 'ALP 11',
                'name' => 'Glee Club',
                'description' => 'Music Theatre Club (voice, music theatre, instruments)',
                'adviser' => 16,
            ],
            [
                'nature' => 'ALP 12',
                'name' => 'Red Cross Youth Club',
                'description' => 'Red Cross Youth Club',
                'adviser' => 17,
            ],
            [
                'nature' => 'ALP 13',
                'name' => 'Math Club',
                'description' => 'Math Club (For Accreditation)',
                'adviser' => 18,
            ],
            [
                'nature' => 'ALP 14',
                'name' => 'Football Club',
                'description' => 'Football Club',
                'adviser' => 19,
            ],
            [
                'nature' => 'ALP 15',
                'name' => 'Frisbee Club',
                'description' => 'Frisbee Club',
                'adviser' => 20,
            ],
        ];

        foreach ($clubs as $club) {
            $alp = Club::create([
                'name' => $club['name'],
                'nature' => $club['nature'],
                'description' => $club['description'],
                'status' => 'active',
            ]);
            $alp->clubRegister()->create([
                'club_id' => $alp->id,
                'user_id' => $club['adviser'],
                'school_year_id' => SchoolYear::current()->id,
                'status' => 'active',
            ]);
        }
    }
}
