@php
    $isEdit = ($mode ?? 'create') === 'edit';
    $currentFormMode = old('form_mode', 'create');
    $oldApplies = (! $isEdit && $currentFormMode === 'create') || ($isEdit && (string) old('menu_id') === (string) $menu?->id);
    $value = fn (string $field, mixed $default = null) => $oldApplies ? old($field, $default) : $default;
    $fieldPrefix = $isEdit ? 'edit_' : 'create_';
@endphp

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
    <select id="{{ $fieldPrefix }}menu_category_id" name="menu_category_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        <option value="">Pilih kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) $value('menu_category_id', $menu?->menu_category_id) === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('menu_category_id') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Menu</label>
    <input id="{{ $fieldPrefix }}name" name="name" type="text" value="{{ $value('name', $menu?->name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
    @error('name') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Singkat</label>
    <input id="{{ $fieldPrefix }}short_description" name="short_description" type="text" value="{{ $value('short_description', $menu?->short_description) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
    @error('short_description') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Harga</label>
    <input id="{{ $fieldPrefix }}price" name="price" type="number" min="0" value="{{ $value('price', $menu?->price) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
    @error('price') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div class="lg:col-span-2">
    <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Detail</label>
    <textarea id="{{ $fieldPrefix }}long_description" name="long_description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">{{ $value('long_description', $menu?->long_description) }}</textarea>
    @error('long_description') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Ilustrasi</label>
    <select id="{{ $fieldPrefix }}hero" name="hero" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @foreach ($heroes as $hero)
            <option value="{{ $hero }}" @selected($value('hero', $menu?->hero ?? 'kopiSusu') === $hero)>{{ $hero }}</option>
        @endforeach
    </select>
    @error('hero') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Menu</label>
    @if ($isEdit)
        <div id="edit_photo_preview_wrapper" class="{{ $menu?->photo_path ? '' : 'hidden' }} mb-3">
            <img id="edit_photo_preview" src="{{ $menu?->photo_path ? route('public.menus.photo.show', $menu) : '' }}" alt="Preview foto menu" class="h-20 w-20 rounded-2xl border border-slate-200 object-cover">
        </div>
    @endif
    <input id="{{ $fieldPrefix }}photo" name="photo" type="file" accept="image/*" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
    <p class="mt-2 text-xs text-slate-400">{{ $isEdit ? 'Kosongkan jika foto tidak ingin diganti.' : 'Foto akan tampil di halaman /menu untuk pengunjung.' }}</p>
    @error('photo') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Tag</label>
    <input id="{{ $fieldPrefix }}tag" name="tag" type="text" value="{{ $value('tag', $menu?->tag) }}" placeholder="Populer / Baru" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
    @error('tag') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan</label>
    <input id="{{ $fieldPrefix }}sort_order" name="sort_order" type="number" min="0" value="{{ $value('sort_order', $menu?->sort_order ?? 10) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
    @error('sort_order') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
    <select id="{{ $fieldPrefix }}status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        <option value="publish" @selected($value('status', $menu?->status ?? 'publish') === 'publish')>Publish</option>
        <option value="arsip" @selected($value('status', $menu?->status) === 'arsip')>Arsip</option>
    </select>
    @error('status') @if ($oldApplies) <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @endif @enderror
</div>
