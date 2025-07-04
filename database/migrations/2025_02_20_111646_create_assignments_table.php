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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('urn')->nullable();
            $table->string('category')->nullable();
            $table->string('subject')->nullable();
            $table->string('description')->nullable();
            $table->integer('deal_price')->nullable();
             $table->string('deal_price_unit')->nullable();
            $table->integer('deal_price_amount')->nullable();
            $table->integer('payment_id')->nullable();
            $table->enum('is_active',['inactive','active']);
            $table->tinyInteger('deal_closed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
