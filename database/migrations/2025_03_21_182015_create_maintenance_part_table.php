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
        Schema::create('maintenance_part', function (Blueprint $table) {
            $table->uuid('maintenance_id');
            $table->uuid('part_id');

            $table->text('observation')->nullable();

            $table->primary(['maintenance_id', 'part_id']);

            $table->foreign('maintenance_id')
                ->references('id')->on('maintenances')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')->on('parts')
                ->onDelete('cascade');

            $table->string('type', 3);
            $table->integer('quantity')->unsigned();
            $table->decimal('cost', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_part');
    }
};
