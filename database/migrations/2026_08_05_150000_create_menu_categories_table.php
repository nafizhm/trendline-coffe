<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('style_class')->default('kopi');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('menu_categories')->insert([
            [
                'name' => 'Kopi',
                'slug' => 'kopi',
                'style_class' => 'kopi',
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non-Kopi',
                'slug' => 'nonkopi',
                'style_class' => 'nonkopi',
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cemilan',
                'slug' => 'cemilan',
                'style_class' => 'cemilan',
                'sort_order' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_categories');
    }
};
