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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('urn')->nullable();
            $table->integer('space')->nullable();
            $table->string('type')->nullable();
            $table->integer('ask_price')->nullable();
            $table->string('ask_price_unit')->nullable();
            $table->integer('ask_price_amount')->nullable();
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->string('state')->nullable();
            
            $table->tinyInteger('deal_closed')->default(0); // 1 for deal closed.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status',['inactive','active']);
            $table->integer('payment_id')->nullable();
            $table->boolean('home_featured')->default(false);
            $table->tinyInteger('deal_closed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
