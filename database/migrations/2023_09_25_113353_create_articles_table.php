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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('title_en');
            $table->text('content_en')->nullable();
            $table->text('title_de');
            $table->text('content_de')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_published')->default('false');
            $table->text('created_from')->nullable();
            $table->text('updated_from')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
