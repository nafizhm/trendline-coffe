<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $categoryKey = $this->normalizeCategoryKey((string) $request->query('category'));
        $category = $this->resolvePublicCategory($categoryKey);

        $videos = Video::query()
            ->with('category')
            ->where('status', 'publish')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view('public.videos.index', [
            'videos' => $videos,
            'categories' => $this->videoCategories()->get(),
            'activeCategoryKey' => $categoryKey,
        ]);
    }

    public function index(): View
    {
        return view('videos.index', [
            'videos' => Video::query()
                ->with('category')
                ->orderBy('category_id')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get(),
            'categories' => $this->videoCategories()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateVideo($request);

        Video::create($data);

        return redirect()->route('videos.index')->with('status', 'Video edukasi berhasil ditambahkan.');
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validateVideo($request);

        $video->update($data);

        return redirect()->route('videos.index')->with('status', 'Video edukasi berhasil diperbarui.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('videos.index')->with('status', 'Video edukasi berhasil dihapus.');
    }

    public function moveUp(Video $video): RedirectResponse
    {
        $orderedVideos = $this->orderedVideosForCategory($video->category_id);
        $currentIndex = $orderedVideos->search(fn (Video $orderedVideo) => $orderedVideo->id === $video->id);
        $previousVideo = $currentIndex !== false && $currentIndex > 0
            ? $orderedVideos->get($currentIndex - 1)
            : null;

        if ($previousVideo) {
            $this->swapVideoOrder($video, $previousVideo);
        }

        return redirect()->route('videos.index')->with('status', 'Urutan video edukasi berhasil diperbarui.');
    }

    public function moveDown(Video $video): RedirectResponse
    {
        $orderedVideos = $this->orderedVideosForCategory($video->category_id);
        $currentIndex = $orderedVideos->search(fn (Video $orderedVideo) => $orderedVideo->id === $video->id);
        $nextVideo = $currentIndex !== false && $currentIndex < $orderedVideos->count() - 1
            ? $orderedVideos->get($currentIndex + 1)
            : null;

        if ($nextVideo) {
            $this->swapVideoOrder($video, $nextVideo);
        }

        return redirect()->route('videos.index')->with('status', 'Urutan video edukasi berhasil diperbarui.');
    }

    private function validateVideo(Request $request): array
    {
        return $request->validate([
            'published_at' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', Rule::exists('categories', 'id')->where('type', Category::TYPE_VIDEO)],
            'youtube_code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9_-]+$/'],
            'status' => ['required', 'in:publish,arsip'],
            'admin_name' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ], [
            'published_at.required' => 'Tanggal wajib diisi.',
            'title.required' => 'Judul video edukasi wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'youtube_code.required' => 'Kode Youtube wajib diisi.',
            'youtube_code.regex' => 'Kode Youtube hanya boleh berisi huruf, angka, strip, atau underscore.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus publish atau arsip.',
            'admin_name.required' => 'Nama admin wajib diisi.',
            'sort_order.required' => 'Urutan wajib diisi.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan minimal 0.',
        ]);
    }

    private function resolvePublicCategory(string $categoryKey): ?Category
    {
        if (blank($categoryKey)) {
            return null;
        }

        $normalizedKey = $this->normalizeCategoryKey($categoryKey);
        $aliases = [
            'video-panduan' => ['panduan'],
            'analisa' => ['analisis'],
            'teknical' => ['technical', 'teknikal'],
        ][$normalizedKey] ?? [];
        $keys = array_merge([$normalizedKey], $aliases);

        return Category::query()
            ->where('type', Category::TYPE_VIDEO)
            ->get()
            ->first(function (Category $category) use ($keys) {
                $normalizedName = $this->normalizeCategoryKey($category->name);

                return in_array($normalizedName, $keys, true)
                    || (in_array('emas', $keys, true) && str_contains($normalizedName, 'emas'));
            });
    }

    private function normalizeCategoryKey(string $value): string
    {
        return str_replace([' ', '_'], '-', strtolower(trim($value)));
    }

    private function videoCategories()
    {
        return Category::query()
            ->where('type', Category::TYPE_VIDEO)
            ->orderBy('name');
    }

    private function swapVideoOrder(Video $firstVideo, Video $secondVideo): void
    {
        $firstOrder = $firstVideo->sort_order;

        $firstVideo->update(['sort_order' => $secondVideo->sort_order]);
        $secondVideo->update(['sort_order' => $firstOrder]);
    }

    private function orderedVideosForCategory(int $categoryId)
    {
        return Video::query()
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->values();
    }
}
