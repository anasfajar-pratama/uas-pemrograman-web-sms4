<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('number');
            $table->enum('section', ['teori', 'logika', 'coding']);
            $table->enum('type', ['pilihan_ganda', 'isian', 'coding'])->default('isian');
            $table->text('question_text');
            $table->text('answer_key');
            $table->json('options')->nullable();
            $table->json('keywords')->nullable();
            $table->unsignedTinyInteger('points')->default(5);
            $table->timestamps();

            $table->unique(['exam_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
