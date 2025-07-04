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
        Schema::create('buyer_noc_trademark', function (Blueprint $table) {
            $table->id();
            $table->foreignId('noc_trademark_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained()->onDelete('cascade');
            $table->enum('is_active',['inactive','active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_noc_trademark');
    }
};
