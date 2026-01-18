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
        Schema::create('episodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('anime_id')->constrained()->onDelete('cascade');
            $table->integer('tmdb_id')->nullable();
            $table->string('title')->nullable();
            $table->text('overview')->nullable();
            $table->string('still_path')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->date('air_date')->nullable();
            $table->integer('season_number');
            $table->integer('episode_number');
            $table->integer('absolute_episode_number')->nullable();
            $table->integer('duration')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();

            $table->index(['anime_id', 'season_number', 'episode_number'], 'episodes_anime_season_episode_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
