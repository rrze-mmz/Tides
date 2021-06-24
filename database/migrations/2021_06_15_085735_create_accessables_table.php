<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessables', function (Blueprint $table) {
            $table->primary(['acl_id','accessable_id','accessable_type']);

            $table->foreignId('acl_id')->references('id')->on('acls');
            $table->foreignId('accessable_id');
            $table->string('accessable_type');

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
        Schema::dropIfExists('accessables');
    }
}
