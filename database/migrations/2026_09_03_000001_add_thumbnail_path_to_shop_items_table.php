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
        if (Schema::hasTable('shop_items') && !Schema::hasColumn('shop_items', 'thumbnail_path')) {
            Schema::table('shop_items', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable()->after('image_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shop_items') && Schema::hasColumn('shop_items', 'thumbnail_path')) {
            Schema::table('shop_items', function (Blueprint $table) {
                $table->dropColumn('thumbnail_path');
            });
        }
    }
};
