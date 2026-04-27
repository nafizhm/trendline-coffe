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
        Schema::create('daily_signals', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['forex', 'saham']);
            $table->string('symbol', 50);
            $table->string('pair_name');
            $table->enum('position', ['buy', 'sell']);
            $table->string('entry_value', 50);
            $table->string('target_value', 50);
            $table->string('stop_value', 50);
            $table->text('description');
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_signals');
    }
};
