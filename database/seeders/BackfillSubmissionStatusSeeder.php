<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Submission;

class BackfillSubmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $submissions = Submission::all();
        foreach ($submissions as $submission) {
            $now = now();
            $userId = $submission->status === 'pending' ? $submission->user_id : 1;
            // Step 1: Add pending status if not already pending
            if ($submission->status !== 'pending') {
                DB::table('submission_trackers')->insert([
                    'submission_id' => $submission->id,
                    'status' => 'pending',
                    'updated_by' => $submission->user_id,
                    'created_at' => $submission->created_at,
                    'updated_at' => $submission->created_at,
                ]);
            }

            // Step 2: Add current status (whether it's pending or something else)

            DB::table('submission_trackers')->insert([
                'submission_id' => $submission->id,
                'status' => $submission->status,
                'updated_by' => $userId,
                'created_at' => $submission->updated_at,
                'updated_at' => $submission->updated_at,
            ]);
        }
    }
}
