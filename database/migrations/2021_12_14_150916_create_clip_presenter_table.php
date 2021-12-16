<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClipPresenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clip_presenter', function (Blueprint $table) {
            $table->primary(['clip_id', 'presenter_id']);
            $table->foreignId('clip_id')->references('id')->on('clips')->onDelete('cascade');
            $table->foreignId('presenter_id')->references('id')->on('presenters')->onDelete('cascade');
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
        Schema::dropIfExists('clip_presenter');
    }
}
