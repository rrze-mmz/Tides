<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sqlite_stats';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (App::environment('testing')) {
            Schema::create('stats', function (Blueprint $table) {
                $table->bigIncrements('stats_id');
                $table->smallInteger('version');
                $table->bigInteger('counter');
                $table->date('doa');
                $table->unsignedBigInteger('resourceid');
                $table->unsignedInteger('serviceid');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
