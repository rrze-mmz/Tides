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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->string('website_url')->nullable();
            $table->string('spotify_url')->nullable();
            $table->string('apple_podcasts_url')->nullable();
            $table->string('google_podcasts_url')->nullable();
            $table->bigInteger('old_podcast_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
