<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (app()->environment('testing')) {
            Schema::create('logs', function (Blueprint $table) {
                $table->bigIncrements('log_id');
                $table->unsignedBigInteger('resource_id');
                $table->unsignedInteger('service_id');
                $table->date('access_date');
                $table->dateTime('access_time');
                $table->ipAddress('remove_addr');
                $table->string('remote_host');
                $table->string('remote_user');
                $table->string('script_name');
                $table->boolean('is_counted');
                $table->timestamps(); // This automatically adds created_at and updated_at
                $table->boolean('is_valid');
                $table->boolean('in_range');
                $table->string('referer')->nullable();
                $table->text('query')->nullable();
                $table->boolean('is_akami');
                $table->string('server')->default(env('APP_ENV', 'production'))->nullable();
                $table->string('range')->nullable();
                $table->string('response');
                $table->ipAddress('real_ip');
                $table->string('num_ip');
                $table->dateTime('last_modified_at');
                $table->string('last_modified_from')->nullable();
                $table->string('bot_name')->nullable();
                $table->string('city')->nullable();
                $table->char('country', 2)->nullable();
                $table->string('counter3')->nullable();
                $table->boolean('is_bot');
                $table->string('region');
                $table->string('region_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats_logs');
    }
};
