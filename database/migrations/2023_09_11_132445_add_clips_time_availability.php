<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->boolean('has_time_availability')->default(false);
            $table->timestamp('time_availability_start')->nullable();
            $table->timestamp('time_availability_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->dropColumn([
                'has_time_availability', 'time_availability_start', 'time_availability_end',
            ]);
        });
    }
};
