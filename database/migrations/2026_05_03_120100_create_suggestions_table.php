<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name')->nullable();
            $table->string('table_number')->nullable();
            $table->text('suggestion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
