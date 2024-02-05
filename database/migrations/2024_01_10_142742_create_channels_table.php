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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('url_handle')->unique(); // URL handle for the channel
            $table->unsignedBigInteger('owner_id')
                ->nullable()
                ->references('id'
                )->on('users')
                ->nullOnDelete()
                ->index(); // a channel belongs to a user
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('banner_url')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
