<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presenters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_degree_id')
                ->nullable()
                ->references('id')
                ->on('academic_degrees')
                ->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('presenters');
    }
}
