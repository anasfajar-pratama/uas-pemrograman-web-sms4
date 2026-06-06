<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('last_active_at')->nullable();
            $table->unsignedInteger('elapsed_seconds')->default(0);
            $table->timestamp('finished_at')->nullable();
            $table->unsignedTinyInteger('expected_grade')->nullable();
            $table->text('grade_reason')->nullable();
            $table->unsignedTinyInteger('estimated_grade')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
