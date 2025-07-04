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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->enum('service_type',['property']); // Type of service
            $table->unsignedBigInteger('service_id'); // Reference to a service
            $table->decimal('amount', 10, 2); // Payment amount
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded']); // Payment status
            $table->string('payment_method'); // Payment method used
            $table->string('transaction_id')->nullable(); // 
            $table->string('payment_from')->nullable(); 
            $table->string('payment_type')->nullable();
            $table->text('notes')->nullable(); // Optional notes
            $table->dateTime('service_start_date'); // Start date of the service
            $table->dateTime('service_end_date'); // End date of the service
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
