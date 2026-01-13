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
        Schema::create('profiles', function (Blueprint $table) {
            $table->foreignUuid('user_id')->primary()->constrained()->onDelete('cascade');
            $table->string('username')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('avatar_url')->default('/default-avatar.webp');
            $table->string('banner_url')->default('/banner-placeholder.webp');
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->json('social_media')->nullable();
            $table->string('age')->nullable();
            $table->string('role')->default('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
