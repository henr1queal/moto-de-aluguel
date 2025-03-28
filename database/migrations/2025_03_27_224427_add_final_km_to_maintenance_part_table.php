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
        Schema::table('maintenance_part', function (Blueprint $table) {
            $table->integer('initial_km')->after('part_id');
            $table->integer('final_km')->nullable()->default(null)->after('initial_km');
            $table->date('changed_in')->nullable()->useCurrent()->after('final_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_part', function (Blueprint $table) {
            $table->dropColumn([
                'final_km',
                'changed_in',
            ]);
        });
    }
};
