<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
        <input id="published_at" name="published_at" type="date" value="{{ old('published_at', isset($contentItem) ? (optional($contentItem->published_at)->format('Y-m-d') ?: $contentItem->published_at) : now()->toDateString()) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @error('published_at')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis</label>
        <input id="type" name="type" type="text" value="{{ old('type', $contentItem->type ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        @error('type')
            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-5">
    <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Isi Konten</label>
    <textarea id="content" name="content" class="js-summernote">{{ old('content', $contentItem->content ?? '') }}</textarea>
    @error('content')
        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
    @enderror
</div>
