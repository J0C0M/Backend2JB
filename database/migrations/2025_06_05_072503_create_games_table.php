<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('target_word');
            $table->string('status')->default('waiting'); // waiting, in_progress, completed
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('player1_guesses')->nullable();
            $table->json('player2_guesses')->nullable();
            $table->integer('max_attempts')->default(6);
            $table->boolean('is_multiplayer')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
