<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id('org_id');
            $table->string('name', 255);
            $table->foreignId('parent_org_id')->nullable();
            $table->string('orgno', 255);
            $table->string('shortname');
            $table->string('staff')->nullable();
            $table->date('startdate');
            $table->date('enddate');
            $table->date('operationstartdate');
            $table->date('operationenddate');
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
        Schema::dropIfExists('organizations');
    }
}
