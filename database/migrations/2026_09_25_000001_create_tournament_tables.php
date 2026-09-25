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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('max_participants')->default(8); // 4, 8, 16, 32
            $table->enum('status', ['draft', 'registration', 'active', 'completed', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('prize_coins')->default(0);
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('runner_up_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('seed_number')->default(1);
            $table->enum('status', ['active', 'eliminated', 'winner'])->default('active');
            $table->integer('final_rank')->nullable();
            $table->timestamps();

            $table->unique(['tournament_id', 'user_id']);
        });

        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->integer('round')->default(1); // 1: Quarterfinal/Babak 1, 2: Semifinal, 3: Final
            $table->integer('match_number')->default(1); // Nomor urut match dalam babak tersebut
            $table->foreignId('player1_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('player2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['waiting_players', 'ready_check', 'in_progress', 'completed', 'forfeited'])->default('waiting_players');
            $table->boolean('ready_p1')->default(false);
            $table->boolean('ready_p2')->default(false);
            $table->timestamp('ready_deadline')->nullable();
            $table->string('room_id')->nullable()->unique();
            $table->integer('spectator_count')->default(0);
            $table->timestamps();
        });

        Schema::create('tournament_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rank')->default(1); // 1 = Juara 1, 2 = Juara 2, 3 = Juara 3
            $table->string('tournament_title');
            $table->string('trophy_badge')->default('gold_trophy.png');
            $table->unsignedBigInteger('prize_coins')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_winners');
        Schema::dropIfExists('tournament_matches');
        Schema::dropIfExists('tournament_participants');
        Schema::dropIfExists('tournaments');
    }
};
