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
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('podcast_id');
            $table->integer('episode_number');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->longText('transcription')->nullable();
            $table->integer('image_id')->nullable()->references('id')->on('images')->nullOndelete();
            $table->string('audio_url');
            $table->string('spotify_url')->nullable();
            $table->string('apple_podcasts_url')->nullable();
            $table->string('google_podcasts_url')->nullable();
            $table->bigInteger('old_episode_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('podcast_id')->references('id')->on('podcasts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_episodes');
    }
};
