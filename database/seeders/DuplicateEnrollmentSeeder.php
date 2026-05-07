<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DuplicateEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = DB::table('enrollments')->get();

        foreach ($enrollments as $enrollment) {

            $data = (array) $enrollment;

            // Remove old ID so Laravel creates a NEW row
            unset($data['id']);

            // Assign new school year
            $data['school_year_id'] = 2;

            // Optional fresh timestamps
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            // Insert as NEW entry
            DB::table('enrollments')->insert($data);
        }
    }
}
