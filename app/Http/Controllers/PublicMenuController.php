<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = MenuCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $menus = Menu::query()
            ->with('category')
            ->where('status', 'publish')
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderBy('menu_category_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->values()
            ->map(fn (Menu $menu) => [
                'id' => $menu->id,
                'cat' => $menu->category?->slug,
                'name' => $menu->name,
                'desc' => $menu->short_description,
                'longDesc' => $menu->long_description,
                'price' => $menu->price,
                'hero' => $menu->hero,
                'photoUrl' => $menu->photo_path ? route('public.menus.photo.show', $menu) : null,
                'tag' => $menu->tag,
            ]);

        return view('public.menu.index', [
            'categories' => $categories,
            'menus' => $menus,
            'tableNumber' => str_pad((string) $request->query('meja', '04'), 2, '0', STR_PAD_LEFT),
        ]);
    }
}
