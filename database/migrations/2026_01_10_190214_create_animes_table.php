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
            $table->string('logo_path')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->date('release_date')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->index()->nullable();
            $table->enum('media_type', ['movie', 'tv'])->default('tv')->index();
            $table->enum('structure_type', ['seasonal', 'absolute'])->default('seasonal');
            $table->json('genres')->nullable();
            $table->json('characters')->nullable();
            $table->integer('hero_order')->default(0)->index();
            $table->integer('vote_count')->nullable();
            $table->string('trailer_key')->nullable();
            $table->timestamps();

            $table->index('created_at');
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
