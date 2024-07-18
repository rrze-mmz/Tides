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
            $table->integer('episode_number');
            $table->date('recording_date')->nullable()->default('now');
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('podcast_id');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->longText('transcription')->nullable();
            $table->integer('image_id')->nullable()->references('id')->on('images')->nullOndelete();
            $table->boolean('is_published')->default(true);
            $table->string('website_url')->nullable();
            $table->string('spotify_url')->nullable();
            $table->string('apple_podcasts_url')->nullable();
            $table->bigInteger('old_episode_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('owner_id')->nullable()->references('id')->on('users')->nullOnDelete()->index();
            $table->timestamps();

            $table->foreign('podcast_id')->references('id')->on('podcasts')->onDelete('cascade')->index();
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
