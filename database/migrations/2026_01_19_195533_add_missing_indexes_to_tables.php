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
        // Comments: Optimize queries for anime/episode comments
        Schema::table('comments', function (Blueprint $table) {
            $table->index(['anime_id', 'episode_id', 'parent_id'], 'comments_anime_episode_parent_idx');
        });

        // Reviews: Optimize queries for anime reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('anime_id');
        });

        // Reports: Optimize queries by status filtering
        Schema::table('reports', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_anime_episode_parent_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['anime_id']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
