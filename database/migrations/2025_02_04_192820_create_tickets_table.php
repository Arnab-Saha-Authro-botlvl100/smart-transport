<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('bus_id');
        $table->unsignedBigInteger('route_id'); // Link to route
        $table->string('seat_number');
        $table->string('ticket_number');
        $table->string('date');
        $table->decimal('price', 8, 2); // Price for the ticket
        $table->enum('status', ['booked', 'cancelled', 'fit'])->default('booked');
        $table->timestamps();

    });
}


    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
