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
            $table->unsignedTinyInteger('number')->unique();
            $table->enum('section', ['teori', 'logika', 'coding']);
            $table->text('question_text');
            $table->text('answer_key');
            $table->json('keywords')->nullable(); // untuk estimasi nilai
            $table->unsignedTinyInteger('points')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
