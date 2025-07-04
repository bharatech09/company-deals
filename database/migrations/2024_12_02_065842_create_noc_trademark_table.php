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
        Schema::create('noc_trademarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('urn')->nullable();
            $table->string('wordmark')->nullable();
            $table->string('application_no')->nullable();
            $table->string('class_no')->nullable();
            $table->string('proprietor')->nullable();
            $table->enum('status',['','VALID','PROTECTION GRANTED'])->nullable();
            $table->dateTime('valid_upto')->nullable();
            $table->string('description')->nullable();
            $table->integer('ask_price')->nullable();
            $table->string('ask_price_unit')->nullable();
            $table->integer('ask_price_amount')->nullable();
            $table->integer('payment_id')->nullable();
            $table->enum('is_active',['inactive','active']);
            $table->tinyInteger('deal_closed')->default(0);
            $table->boolean('home_featured')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noc_trademark');
    }
};
