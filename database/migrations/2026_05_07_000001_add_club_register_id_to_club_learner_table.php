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
            $table->foreignId('club_register_id')
                ->nullable()
                ->after('club_id')
                ->constrained('club_registers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_learner', function (Blueprint $table) {
            $table->dropConstrainedForeignId('club_register_id');
        });
    }
};
