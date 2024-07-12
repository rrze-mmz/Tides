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
        Schema::table('presentables', function (Blueprint $table) {
            $table->boolean('primary')->default('true');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentables', function (Blueprint $table) {
            $table->dropColumn([
                'primary',
            ]);
        });
    }
};
//$table->boolean('is_published')->default('false');
