<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('admin_name');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('admin_name');
        });

        DB::table('articles')
            ->orderBy('category_id')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy('category_id')
            ->each(function ($articles) {
                $articles->values()->each(function ($article, int $index) {
                    DB::table('articles')
                        ->where('id', $article->id)
                        ->update(['sort_order' => $index + 1]);
                });
            });

        DB::table('videos')
            ->orderBy('category_id')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy('category_id')
            ->each(function ($videos) {
                $videos->values()->each(function ($video, int $index) {
                    DB::table('videos')
                        ->where('id', $video->id)
                        ->update(['sort_order' => $index + 1]);
                });
            });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
