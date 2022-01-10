<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentables', function (Blueprint $table) {
            $table->primary(['presenter_id', 'presentable_id', 'presentable_type']);

            $table->foreignId('presenter_id')->references('id')->on('presenters')->onDelete('cascade');
            $table->foreignId('presentable_id');
            $table->string('presentable_type');

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
        Schema::dropIfExists('presentables');
    }
}
