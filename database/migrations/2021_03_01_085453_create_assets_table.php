<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('original_file_name');
            $table->string('disk');
            $table->string('path');
            $table->integer('width');
            $table->integer('height');
            $table->integer('duration');
            $table->smallInteger('type')->nullable();
            $table->uuid('guid')->unique();
            $table->string('player_preview')->nullable();
            $table->datetime('converted_for_downloading_at')->nullable();
            $table->datetime('converted_for_streaming_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
