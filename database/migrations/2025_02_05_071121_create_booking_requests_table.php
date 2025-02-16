<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('passenger_id'); // Assuming passengers are in 'users' table
            $table->integer('bus_id'); // Assuming passengers are in 'users' table
            $table->json('ticket_ids'); // Store multiple ticket IDs as JSON
            $table->decimal('total_amount', 10, 2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('booking_requests');
    }
};
