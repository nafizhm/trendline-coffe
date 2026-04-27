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
        Schema::table('app_settings', function (Blueprint $table) {
            $table->text('address')->nullable()->after('direct_wa_number');
            $table->string('operational_hours')->nullable()->after('address');
            $table->text('reservation_info')->nullable()->after('operational_hours');
            $table->longText('google_maps_embed')->nullable()->after('reservation_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'operational_hours',
                'reservation_info',
                'google_maps_embed',
            ]);
        });
    }
};
