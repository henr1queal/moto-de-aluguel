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
        Schema::create('rentals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('landlord_name');
            $table->string('landlord_cpf');
            $table->string('driver_license_number');
            $table->date('driver_license_issue_date');
            $table->date('birth_date');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('mother_name');
            $table->string('father_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('cost', 10, 2);
            $table->decimal('deposit', 10, 2);
            $table->string('zip_code');
            $table->string('state');
            $table->string('city');
            $table->string('neighborhood');
            $table->string('street');
            $table->string('house_number')->nullable();
            $table->string('complement')->nullable();
            $table->string('photo');
            $table->foreignUuid('vehicle_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
