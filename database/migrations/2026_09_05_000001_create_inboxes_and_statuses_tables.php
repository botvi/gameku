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
        Schema::create('inboxes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['info', 'reward', 'announcement', 'warning'])->default('info');
            $table->enum('target_type', ['all', 'user'])->default('all');
            $table->foreignId('target_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('reward_coins')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_inbox_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbox_id')->constrained('inboxes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_claimed')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['inbox_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_inbox_statuses');
        Schema::dropIfExists('inboxes');
    }
};
