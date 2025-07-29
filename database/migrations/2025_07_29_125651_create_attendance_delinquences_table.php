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
        Schema::create('attendance_delinquences', function (Blueprint $table) {
            $table->id();
            $table->boolean('resolved')->default(false);
            $table->string('link')->nullable();
            $table->foreignId('resolved_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('club_attendance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('club_attendance_learner_id')->constrained('club_attendance_learner')->cascadeOnDelete();
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_delinquences');
    }
};
