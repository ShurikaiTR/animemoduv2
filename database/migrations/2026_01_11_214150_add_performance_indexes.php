<?php

declare(strict_types=1);

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
        // Animes table indexes
        Schema::table('animes', function (Blueprint $table) {
            $table->index('is_featured');
            $table->index('status');
            $table->index('media_type');
            $table->index('created_at');
        });

        // Episodes composite index for efficient season/episode queries
        Schema::table('episodes', function (Blueprint $table) {
            $table->index(['anime_id', 'season_number', 'episode_number'], 'episodes_anime_season_episode_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['status']);
            $table->dropIndex(['media_type']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->dropIndex('episodes_anime_season_episode_idx');
        });
    }
};
