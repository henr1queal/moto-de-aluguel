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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('brand', 20);
            $table->string('model', 20);
            $table->year('year');
            $table->string('license_plate', 8)->unique();
            $table->string('renavam', 11)->unique();
            $table->integer('actual_km', false, true)->default(0);
            $table->integer('first_declared_km', false, true)->default(0);
            $table->integer('revision_period', false, true);
            $table->integer('oil_period', false, true);
            $table->integer('next_oil_change', false, true);
            $table->integer('next_revision', false, true);
            $table->decimal('protection_value');
            $table->foreignUuid('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
