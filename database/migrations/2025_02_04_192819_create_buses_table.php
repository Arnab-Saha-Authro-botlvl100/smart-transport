<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('bus_number')->unique();
            $table->string('name');
            $table->integer('capacity');
            $table->integer('user');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('driver_id')->nullable(); // Assuming drivers are in the users table
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('buses');
    }
};
