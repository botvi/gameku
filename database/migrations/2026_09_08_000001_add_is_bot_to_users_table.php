<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'is_bot')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_bot')->default(false)->after('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'is_bot')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_bot');
            });
        }
    }
};