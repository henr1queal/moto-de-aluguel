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
        Schema::table('rentals', function (Blueprint $table) {
            $table->boolean('contract_break_fee')->default(false)->after('deposit');
            $table->decimal('contract_break_fee_value', 10, 2)->nullable()->after('contract_break_fee');
            $table->boolean('damage_fee')->default(false)->after('contract_break_fee_value');
            $table->decimal('damage_fee_value', 10, 2)->nullable()->after('damage_fee');
            $table->date('stop_date')->nullable()->after('damage_fee_value');
            $table->text('finish_observation')->nullable()->after('stop_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'contract_break_fee',
                'contract_break_fee_value',
                'damage_fee',
                'damage_fee_value',
                'stop_date',
                'finish_observation',
            ]);
        });
    }
};

