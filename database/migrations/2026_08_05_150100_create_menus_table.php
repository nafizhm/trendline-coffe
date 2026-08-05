<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('short_description');
                $table->text('long_description');
                $table->unsignedInteger('price');
                $table->string('hero')->default('kopiSusu');
                $table->string('tag')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->string('status')->default('publish');
                $table->timestamps();
            });
        }

        $categories = DB::table('menu_categories')->pluck('id', 'slug');

        if (DB::table('menus')->exists()) {
            return;
        }

        DB::table('menus')->insert([
            ['menu_category_id' => $categories['kopi'], 'name' => 'Kopi Susu Gula Aren', 'short_description' => 'Espresso, susu segar, gula aren asli', 'long_description' => 'Espresso house blend dipadukan susu segar dan gula aren asli yang dimasak sendiri setiap pagi. Rasa manis karamel yang pas, disajikan dingin dengan es batu penuh.', 'price' => 22000, 'hero' => 'kopiSusu', 'tag' => 'Populer', 'sort_order' => 10, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['kopi'], 'name' => 'Americano', 'short_description' => 'Espresso ganda, air panas atau dingin', 'long_description' => 'Dua shot espresso house blend diseduh dengan air panas atau air dingin sesuai selera. Pilihan bersih dan ringan untuk yang suka rasa kopi apa adanya.', 'price' => 18000, 'hero' => 'americano', 'tag' => null, 'sort_order' => 20, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['kopi'], 'name' => 'Cappuccino', 'short_description' => 'Espresso, susu steam, foam tebal', 'long_description' => 'Espresso dengan susu steam dan foam tebal berlapis, disajikan hangat dalam cangkir keramik. Tekstur creamy dengan rasa espresso yang tetap terasa jelas.', 'price' => 24000, 'hero' => 'cappuccino', 'tag' => null, 'sort_order' => 30, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['kopi'], 'name' => 'V60 Manual Brew', 'short_description' => 'Biji pilihan minggu ini, diseduh manual', 'long_description' => 'Diseduh manual satu per satu memakai biji pilihan minggu ini, diracik langsung oleh barista di meja seduh. Profil rasa berubah tiap minggu mengikuti biji yang sedang kami jelajahi.', 'price' => 28000, 'hero' => 'v60', 'tag' => 'Baru', 'sort_order' => 40, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['nonkopi'], 'name' => 'Matcha Latte', 'short_description' => 'Matcha ceremonial grade, susu segar', 'long_description' => 'Bubuk matcha ceremonial grade dikocok manual lalu dipadukan susu segar. Rasa earthy yang lembut, tidak terlalu manis, cocok diminum panas maupun dingin.', 'price' => 26000, 'hero' => 'matcha', 'tag' => null, 'sort_order' => 10, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['nonkopi'], 'name' => 'Taro Latte', 'short_description' => 'Krim taro asli, sedikit manis', 'long_description' => 'Krim taro asli dipadukan susu segar untuk rasa manis lembut dengan sedikit nutty. Warna ungu alami, tanpa pewarna tambahan.', 'price' => 25000, 'hero' => 'taro', 'tag' => null, 'sort_order' => 20, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['nonkopi'], 'name' => 'Lemon Tea', 'short_description' => 'Teh hitam, perasan lemon segar', 'long_description' => 'Teh hitam pilihan diseduh kuat lalu diberi perasan lemon segar. Segar dan sedikit asam, pas untuk menemani siang yang panas.', 'price' => 17000, 'hero' => 'lemon', 'tag' => null, 'sort_order' => 30, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['cemilan'], 'name' => 'Pisang Goreng Karamel', 'short_description' => 'Pisang crispy, saus karamel gula aren', 'long_description' => 'Pisang kepok digoreng crispy lalu disiram saus karamel gula aren hangat. Camilan manis gurih favorit banyak pelanggan kami.', 'price' => 19000, 'hero' => 'pisang', 'tag' => 'Populer', 'sort_order' => 10, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['cemilan'], 'name' => 'Croissant Butter', 'short_description' => 'Dipanggang tiap pagi, isi butter', 'long_description' => 'Dipanggang setiap pagi dengan lapisan mentega yang renyah di luar, lembut di dalam. Nikmat dimakan polos atau dicocol kopi.', 'price' => 23000, 'hero' => 'croissant', 'tag' => null, 'sort_order' => 20, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
            ['menu_category_id' => $categories['cemilan'], 'name' => 'French Fries', 'short_description' => 'Kentang goreng, saus sambal rumahan', 'long_description' => 'Kentang goreng renyah disajikan hangat dengan saus sambal rumahan. Pilihan pas untuk teman ngobrol santai di kedai.', 'price' => 16000, 'hero' => 'fries', 'tag' => null, 'sort_order' => 30, 'status' => 'publish', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
