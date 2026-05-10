<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('club_learner', function (Blueprint $table) {
            $table->unique(
                ['club_register_id', 'learner_id'],
                'club_learner_club_register_learner_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_learner', function (Blueprint $table) {
            $table->dropUnique('club_learner_club_register_learner_unique');
        });
    }
};
