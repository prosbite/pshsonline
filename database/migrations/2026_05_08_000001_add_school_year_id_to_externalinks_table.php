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
        Schema::table('externalinks', function (Blueprint $table) {
            $table->foreignId('school_year_id')
                ->nullable()
                ->after('club_register_id')
                ->constrained('school_years')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('externalinks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_year_id');
        });
    }
};
