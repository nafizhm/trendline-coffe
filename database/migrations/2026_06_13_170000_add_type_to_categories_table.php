<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('type')->default('artikel')->after('name');
        });

        $videoCategoryNames = ['Forex', 'Saham', 'Video Panduan', 'Analisa', 'Teknical'];

        foreach ($videoCategoryNames as $name) {
            $existingVideoCategory = DB::table('categories')
                ->where('type', 'video')
                ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                ->first();

            if (! $existingVideoCategory) {
                DB::table('categories')->insert([
                    'name' => $name,
                    'type' => 'video',
                    'description' => 'Kategori video edukasi ' . $name . '.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $videoCategoryIds = DB::table('categories')
            ->where('type', 'video')
            ->get()
            ->mapWithKeys(fn ($category) => [Str::lower($category->name) => $category->id]);

        DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->where('categories.type', 'artikel')
            ->select('videos.id', 'categories.name')
            ->orderBy('videos.id')
            ->get()
            ->each(function ($video) use ($videoCategoryIds) {
                $categoryId = $videoCategoryIds->get(Str::lower($video->name));

                if (! $categoryId) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'name' => $video->name,
                        'type' => 'video',
                        'description' => 'Kategori video edukasi ' . $video->name . '.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('videos')
                    ->where('id', $video->id)
                    ->update(['category_id' => $categoryId]);
            });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
