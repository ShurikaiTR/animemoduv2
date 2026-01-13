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
        Schema::create('animes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('tmdb_id')->unique()->nullable();
            $table->integer('anilist_id')->unique()->nullable();
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->date('release_date')->nullable();
            $table->string('slug')->unique();
            $table->enum('media_type', ['movie', 'tv'])->default('tv');
            $table->enum('structure_type', ['seasonal', 'absolute'])->default('seasonal');
            $table->json('genres')->nullable();
            $table->json('characters')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('vote_count')->nullable();
            $table->string('trailer_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
