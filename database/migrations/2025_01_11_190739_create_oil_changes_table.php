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
        Schema::create('oil_changes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('cost', 10, 2);
            $table->integer('actual_km');
            $table->date('date');
            $table->text('observation')->nullable();
            $table->foreignUuid('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('rental_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('revision_id')->nullable()->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_changes');
    }
};
