<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_signals', function (Blueprint $table) {
            $table->date('signal_date')->nullable()->after('position');
            $table->time('signal_time')->nullable()->after('signal_date');
        });

        DB::table('daily_signals')
            ->whereNull('signal_date')
            ->update([
                'signal_date' => DB::raw('DATE(created_at)'),
                'signal_time' => DB::raw('TIME(created_at)'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_signals', function (Blueprint $table) {
            $table->dropColumn(['signal_date', 'signal_time']);
        });
    }
};
