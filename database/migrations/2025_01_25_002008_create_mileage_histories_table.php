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
        Schema::create('mileage_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('actual_km');
            $table->date('date');
            $table->foreignUuid('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('rental_id')->constrained()->onDelete('cascade');
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mileage_histories');
    }
};
