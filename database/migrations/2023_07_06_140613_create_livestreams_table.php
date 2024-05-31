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
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('url')->nullable();
            $table->text('content_path')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('active')->default(false);
            $table->foreignId('clip_id')->nullable()->references('id')->on('clips')->nullOnDelete();
            $table->string('app_name', 128)->nullable();
            $table->boolean('has_transcoder')->default(false);
            $table->timestamp('time_availability_start')->nullable();
            $table->timestamp('time_availability_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestreams');
    }
};
