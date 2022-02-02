<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalSeriesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('lms_link')->nullable();
            $table->string('opencast_logo_pos', 2)->nullable()->default('TR');
            $table->boolean('ls_auto_reservation')->nullable()->default(true);
            $table->text('ls_reservation_layout')->nullable()->default('sbs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('lms_link');
            $table->dropColumn('opencast_logo_pos');
            $table->dropColumn('ls_auto_reservation');
            $table->dropColumn('ls_reservation_layout');
        });
    }
}
