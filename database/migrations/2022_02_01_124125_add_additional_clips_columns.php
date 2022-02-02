<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalClipsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->string('folder_id', 128)->nullable();
            $table->date('recording_date')->nullable()->default('now');
            $table->string('acronym', 10)->nullable();
            $table->string('opencast_logo_pos', 2)->nullable()->default('TR');
            $table->timestamp('uploaded_at')->nullable();
            $table->boolean('is_livestream')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->dropColumn('folder_id');
            $table->dropColumn('recording_date');
            $table->dropColumn('acronym');
            $table->dropColumn('opencast_logo_pos');
            $table->dropColumn('uploaded_at');
            $table->dropColumn('is_livestream');
        });
    }
}
