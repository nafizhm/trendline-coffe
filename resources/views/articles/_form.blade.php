@php
    $articleModel = $article ?? null;
    $defaultAdminName = old('admin_name', $articleModel?->admin_name ?? auth()->user()?->name ?? '');
    $defaultPublishedDate = old('published_at', optional($articleModel?->published_at)->format('Y-m-d') ?? now()->format('Y-m-d'));
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
        <input id="published_at" name="published_at" type="date" value="{{ $defaultPublishedDate }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @error('published_at')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
        <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
            <option value="">Pilih status</option>
            <option value="publish" @selected(old('status', $articleModel?->status) === 'publish')>Publish</option>
            <option value="arsip" @selected(old('status', $articleModel?->status) === 'arsip')>Arsip</option>
        </select>
        @error('status')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Artikel</label>
        <input id="title" name="title" type="text" value="{{ old('title', $articleModel?->title) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @error('title')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
        <select id="category_id" name="category_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $articleModel?->category_id) === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="admin_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Admin</label>
        <input id="admin_name" name="admin_name" type="text" value="{{ $defaultAdminName }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @error('admin_name')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Isi Artikel</label>
        <textarea id="content" name="content" rows="16" class="js-summernote w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">{{ old('content', $articleModel?->content) }}</textarea>
        @error('content')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="attachment" class="mb-2 block text-sm font-semibold text-slate-700">Upload File Artikel</label>
        @if ($articleModel?->attachment_path)
            <div class="mb-3">
                <a href="{{ route('public.articles.files.show', $articleModel) }}" target="_blank" rel="noreferrer" class="inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Lihat File Saat Ini
                </a>
            </div>
        @endif
        <input id="attachment" name="attachment" type="file" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
        <p class="mt-2 text-xs text-slate-500">Bisa upload file apa pun. Jika file berupa PDF, frontend akan mencoba membukanya langsung di halaman browser.</p>
        @error('attachment')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>
</div>
