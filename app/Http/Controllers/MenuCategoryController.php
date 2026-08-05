<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(): View
    {
        return view('menu-categories.index', [
            'categories' => MenuCategory::query()
                ->withCount('menus')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MenuCategory::create($this->validatedData($request));

        return redirect()->route('menu-categories.index')->with('status', 'Kategori menu berhasil ditambahkan.');
    }

    public function update(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->update($this->validatedData($request, $menuCategory));

        return redirect()->route('menu-categories.index')->with('status', 'Kategori menu berhasil diperbarui.');
    }

    public function destroy(MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->delete();

        return redirect()->route('menu-categories.index')->with('status', 'Kategori menu berhasil dihapus.');
    }

    private function validatedData(Request $request, ?MenuCategory $menuCategory = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('menu_categories', 'slug')->ignore($menuCategory)],
            'style_class' => ['required', 'in:kopi,nonkopi,cemilan'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'style_class.required' => 'Warna kategori wajib dipilih.',
            'sort_order.required' => 'Urutan wajib diisi.',
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $slugExists = MenuCategory::query()
            ->where('slug', $data['slug'])
            ->when($menuCategory, fn ($query) => $query->whereKeyNot($menuCategory->getKey()))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug kategori sudah digunakan.',
            ]);
        }

        return $data;
    }
}
