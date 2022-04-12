<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('type_id')->nullable();
            $table->text('description')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('has_livestream_func')->nullable();
            $table->text('opencast_device_name')->nullable();
            $table->text('name');
            $table->text('url')->nullable();
            $table->text('created_from')->nullable();
            $table->text('updated_from')->nullable();
            $table->text('telephone_number')->nullable();
            $table->string('ip_address')->nullable();
            $table->smallInteger('location_id')->nullable();
            $table->text('camera_url')->nullable();
            $table->text('power_outlet_url')->nullable();
            $table->bigInteger('organization_id')->nullable()->default(191);
            $table->boolean('operational')->nullable()->default(false);
            $table->boolean('is_hybrid')->nullable()->default(false);
            $table->boolean('has_recording_func')->nullable()->default(true);
            $table->text('room_url')->nullable();
            $table->smallInteger('supervisor_id')->nullable()->default(1);
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
        Schema::dropIfExists('devices');
    }
};
