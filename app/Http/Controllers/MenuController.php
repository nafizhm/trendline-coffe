<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MenuController extends Controller
{
    private const HEROES = [
        'kopiSusu',
        'americano',
        'cappuccino',
        'v60',
        'matcha',
        'taro',
        'lemon',
        'pisang',
        'croissant',
        'fries',
    ];

    public function index(): View
    {
        return view('menus.index', [
            'menus' => Menu::query()
                ->with('category')
                ->orderBy('menu_category_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'categories' => MenuCategory::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'heroes' => self::HEROES,
        ]);
    }

    public function showPhoto(Menu $menu): StreamedResponse
    {
        abort_if(blank($menu->photo_path), 404);

        try {
            return Storage::disk('public')->response($menu->photo_path);
        } catch (FileNotFoundException) {
            abort(404);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateMenu($request);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('menus.index')->with('status', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $this->validateMenu($request);

        if ($request->hasFile('photo')) {
            $this->deleteStoredPhoto($menu->photo_path);
            $data['photo_path'] = $request->file('photo')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('menus.index')->with('status', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->deleteStoredPhoto($menu->photo_path);
        $menu->delete();

        return redirect()->route('menus.index')->with('status', 'Menu berhasil dihapus.');
    }

    private function validateMenu(Request $request): array
    {
        return $request->validate([
            'menu_category_id' => ['required', Rule::exists('menu_categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'long_description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'hero' => ['required', Rule::in(self::HEROES)],
            'photo' => ['nullable', 'image', 'max:4096'],
            'tag' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:publish,arsip'],
        ], [
            'menu_category_id.required' => 'Kategori wajib dipilih.',
            'name.required' => 'Nama menu wajib diisi.',
            'short_description.required' => 'Deskripsi singkat wajib diisi.',
            'long_description.required' => 'Deskripsi detail wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'hero.required' => 'Ilustrasi wajib dipilih.',
            'photo.image' => 'Foto menu harus berupa gambar.',
            'photo.max' => 'Foto menu maksimal 4 MB.',
            'status.required' => 'Status wajib dipilih.',
        ]);
    }

    private function deleteStoredPhoto(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
