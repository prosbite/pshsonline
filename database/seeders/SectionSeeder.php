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
        $section1 = ['Aquamarine', 'Opal', 'Sapphire', 'Turquoise'];
        $section2 = ['Anthurium', 'Carnation', 'Daffodil', 'Sunflower'];
        $section3 = ['Barium', 'Calcium', 'Lithium', 'Sodium'];
        $section4 = ['Electron', 'Graviton', 'Neutron', 'Proton'];
        $section5 = ['Mercury', 'Venus', 'Mars'];
        $section6 = ['Del Mundo', 'Orosa', 'Zara'];
        foreach ($section1 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 1,
            ]);
        }
        foreach ($section2 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 2,
            ]);
        }
        foreach ($section3 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 3,
            ]);
        }
        foreach ($section4 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 4,
            ]);
        }
        foreach ($section5 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 5,
            ]);
        }
        foreach ($section6 as $index => $section) {
            \App\Models\Section::create([
                'section_name' => $section,
                'status' => 'active',
                'grade_level_id' => 6,
            ]);
        }
    }
}
