<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = ['Aquamarine', 'Opal', 'Sapphire', 'Turquoise'];
        foreach ($sections as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 1,
            ]);
        }
    }
}
