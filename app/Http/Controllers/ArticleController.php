<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArticleController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $categoryKey = $this->normalizeCategoryKey((string) $request->query('category'));
        $category = $this->resolvePublicCategory($categoryKey);

        $articles = Article::query()
            ->with('category')
            ->where('status', 'publish')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view('public.articles.index', [
            'articles' => $articles,
            'categories' => $this->articleCategories()->get(),
            'activeCategoryKey' => $categoryKey,
        ]);
    }

    public function publicShow(Article $article): View
    {
        abort_unless($article->status === 'publish', 404);

        return view('public.articles.show', [
            'article' => $article->load('category'),
            'attachmentUrl' => $article->attachment_path ? route('public.articles.files.show', $article) : null,
            'isPdfAttachment' => $this->isPdfAttachment($article->attachment_path),
        ]);
    }

    public function showAttachment(Article $article): StreamedResponse
    {
        abort_unless($article->status === 'publish', 404);
        abort_if(blank($article->attachment_path), 404);

        try {
            return Storage::disk('public')->response(
                $article->attachment_path,
                basename($article->attachment_path),
                ['Content-Disposition' => 'inline; filename="' . basename($article->attachment_path) . '"']
            );
        } catch (FileNotFoundException) {
            abort(404);
        }
    }

    public function index(): View
    {
        return view('articles.index', [
            'articles' => Article::query()
                ->with('category')
                ->orderBy('category_id')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('articles.create', [
            'categories' => $this->articleCategories()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateArticle($request);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('articles.index')->with('status', 'Artikel edukasi berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        return view('articles.edit', [
            'article' => $article,
            'categories' => $this->articleCategories()->get(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validateArticle($request);

        if ($request->hasFile('attachment')) {
            $this->deleteStoredFile($article->attachment_path);
            $data['attachment_path'] = $request->file('attachment')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('articles.index')->with('status', 'Artikel edukasi berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteStoredFile($article->attachment_path);
        $article->delete();

        return redirect()->route('articles.index')->with('status', 'Artikel edukasi berhasil dihapus.');
    }

    public function moveUp(Article $article): RedirectResponse
    {
        $orderedArticles = $this->orderedArticlesForCategory($article->category_id);
        $currentIndex = $orderedArticles->search(fn (Article $orderedArticle) => $orderedArticle->id === $article->id);
        $previousArticle = $currentIndex !== false && $currentIndex > 0
            ? $orderedArticles->get($currentIndex - 1)
            : null;

        if ($previousArticle) {
            $this->swapArticleOrder($article, $previousArticle);
        }

        return redirect()->route('articles.index')->with('status', 'Urutan artikel edukasi berhasil diperbarui.');
    }

    public function moveDown(Article $article): RedirectResponse
    {
        $orderedArticles = $this->orderedArticlesForCategory($article->category_id);
        $currentIndex = $orderedArticles->search(fn (Article $orderedArticle) => $orderedArticle->id === $article->id);
        $nextArticle = $currentIndex !== false && $currentIndex < $orderedArticles->count() - 1
            ? $orderedArticles->get($currentIndex + 1)
            : null;

        if ($nextArticle) {
            $this->swapArticleOrder($article, $nextArticle);
        }

        return redirect()->route('articles.index')->with('status', 'Urutan artikel edukasi berhasil diperbarui.');
    }

    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'published_at' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', Rule::exists('categories', 'id')->where('type', Category::TYPE_ARTICLE)],
            'content' => ['required', 'string'],
            'attachment' => ['nullable', 'file'],
            'status' => ['required', 'in:publish,arsip'],
            'admin_name' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ], [
            'published_at.required' => 'Tanggal wajib diisi.',
            'title.required' => 'Judul artikel edukasi wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'content.required' => 'Isi artikel edukasi wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus publish atau arsip.',
            'admin_name.required' => 'Nama admin wajib diisi.',
            'sort_order.required' => 'Urutan wajib diisi.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan minimal 0.',
        ]);
    }

    private function deleteStoredFile(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function isPdfAttachment(?string $path): bool
    {
        return filled($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    private function resolvePublicCategory(string $categoryKey): ?Category
    {
        if (blank($categoryKey)) {
            return null;
        }

        $normalizedKey = $this->normalizeCategoryKey($categoryKey);

        return Category::query()
            ->where('type', Category::TYPE_ARTICLE)
            ->get()
            ->first(function (Category $category) use ($normalizedKey) {
                $normalizedName = $this->normalizeCategoryKey($category->name);

                return $normalizedName === $normalizedKey
                    || ($normalizedKey === 'emas' && str_contains($normalizedName, 'emas'));
            });
    }

    private function normalizeCategoryKey(string $value): string
    {
        return str_replace([' ', '_'], '-', strtolower(trim($value)));
    }

    private function articleCategories()
    {
        return Category::query()
            ->where('type', Category::TYPE_ARTICLE)
            ->orderBy('name');
    }

    private function swapArticleOrder(Article $firstArticle, Article $secondArticle): void
    {
        $firstOrder = $firstArticle->sort_order;

        $firstArticle->update(['sort_order' => $secondArticle->sort_order]);
        $secondArticle->update(['sort_order' => $firstOrder]);
    }

    private function orderedArticlesForCategory(int $categoryId)
    {
        return Article::query()
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->values();
    }
}
