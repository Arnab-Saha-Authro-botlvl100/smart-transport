<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bus_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bus_id');
            $table->unsignedBigInteger('driver_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('tracked_at')->useCurrent();
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('bus_tracking');
    }
};
