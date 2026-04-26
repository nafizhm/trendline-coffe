<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $categoryKey = strtolower((string) $request->query('category'));
        $category = blank($categoryKey)
            ? null
            : Category::query()
                ->get()
                ->first(fn (Category $category) => strtolower($category->name) === $categoryKey);

        $videos = Video::query()
            ->with('category')
            ->where('status', 'publish')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->orderByDesc('published_at')
            ->latest('id')
            ->get();

        return view('public.videos.index', [
            'videos' => $videos,
            'categories' => Category::query()->orderBy('name')->get(),
            'activeCategoryKey' => $categoryKey,
        ]);
    }

    public function index(): View
    {
        return view('videos.index', [
            'videos' => Video::query()
                ->with('category')
                ->latest()
                ->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateVideo($request);

        Video::create($data);

        return redirect()->route('videos.index')->with('status', 'Video berhasil ditambahkan.');
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validateVideo($request);

        $video->update($data);

        return redirect()->route('videos.index')->with('status', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('videos.index')->with('status', 'Video berhasil dihapus.');
    }

    private function validateVideo(Request $request): array
    {
        return $request->validate([
            'published_at' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'youtube_code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9_-]+$/'],
            'status' => ['required', 'in:publish,arsip'],
            'admin_name' => ['required', 'string', 'max:255'],
        ], [
            'published_at.required' => 'Tanggal wajib diisi.',
            'title.required' => 'Judul video wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'youtube_code.required' => 'Kode Youtube wajib diisi.',
            'youtube_code.regex' => 'Kode Youtube hanya boleh berisi huruf, angka, strip, atau underscore.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus publish atau arsip.',
            'admin_name.required' => 'Nama admin wajib diisi.',
        ]);
    }
}
