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
        Schema::table('presenters', function (Blueprint $table) {
            $table->integer('image_id')
                ->nullable()
                ->references('id')
                ->on('images')
                ->nullOndelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presenters', function (Blueprint $table) {
            $table->dropColumn('image_id');
        });
    }
};
