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
        Schema::create('assetables', function (Blueprint $table) {
            $table->primary(['asset_id', 'assetable_id', 'assetable_type']);
            $table->foreignId('asset_id')->references('id')->on('assets')->onDelete('cascade')->index();
            $table->foreignId('assetable_id')->index();
            $table->string('assetable_type');
            $table->boolean('primary')->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assetables');
    }
};
