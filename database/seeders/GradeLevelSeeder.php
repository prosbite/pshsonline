<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gradeLevels = [7, 8, 9, 10, 11, 12];
        foreach ($gradeLevels as $gradeLevel) {
            \App\Models\GradeLevel::create([
                'grade_level' => $gradeLevel,
                'status' => 'active',
            ]);
        }
    }
}
