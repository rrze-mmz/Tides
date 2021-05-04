<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->unsignedBigInteger('series_id')->nullable()->default(null);
            $table->integer('episode')->default('1');

        });
    }

    /**
     * Reverse the migrations.
     *Hal
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
