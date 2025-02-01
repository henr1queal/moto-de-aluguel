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
            $table->boolean('paid');
            $table->decimal('cost', 10, 2);
            $table->date('payment_date');
            $table->date('paid_in');
            $table->text('observation')->nullable();
            $table->foreignUuid('rental_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();            
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
