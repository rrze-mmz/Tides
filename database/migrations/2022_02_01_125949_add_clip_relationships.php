<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClipRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->foreignId('language_id')
                ->default(1)
                ->references('id')
                ->on('languages')
                ->cascadeOnDelete();
            $table->foreignId('context_id')
                ->default(1)
                ->references('id')
                ->on('contexts')
                ->cascadeOnDelete();
            $table->foreignId('format_id')
                ->default(1)
                ->references('id')
                ->on('formats')
                ->cascadeOnDelete();
            $table->foreignId('type_id')
                ->default(1)
                ->references('id')
                ->on('types')
                ->cascadeOnDelete();
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
            $table->dropColumn('language_id');
            $table->dropColumn('context_id');
            $table->dropColumn('format_id');
            $table->dropColumn('type_id');
        });
    }
}
