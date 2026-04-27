<div data-signal-card class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
    @php
        $fieldPrefix = "signals[{$type}-{$index}]";
        $makassarNow = now('Asia/Makassar');
    @endphp

    <input type="hidden" data-field="type" name="{{ $fieldPrefix }}[type]" value="{{ $type }}">
    <div class="mb-4 flex items-center justify-between gap-3">
        <h4 class="text-lg font-black text-slate-900">{{ $type === 'forex' ? 'Signal Forex' : 'Signal Saham' }}</h4>
        <button type="button" data-remove-signal class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">
            Hapus
        </button>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
            <input data-field="signal_date" name="{{ $fieldPrefix }}[signal_date]" type="date" value="{{ $signal['signal_date'] ?? $makassarNow->format('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Jam</label>
            <input data-field="signal_time" name="{{ $fieldPrefix }}[signal_time]" type="time" value="{{ $signal['signal_time'] ?? $makassarNow->format('H:i') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Symbol</label>
            <input data-field="symbol" name="{{ $fieldPrefix }}[symbol]" type="text" value="{{ $signal['symbol'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Pair / Emiten</label>
            <input data-field="pair_name" name="{{ $fieldPrefix }}[pair_name]" type="text" value="{{ $signal['pair_name'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Posisi</label>
            <select data-field="position" name="{{ $fieldPrefix }}[position]" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                <option value="buy" {{ ($signal['position'] ?? 'buy') === 'buy' ? 'selected' : '' }}>Buy</option>
                <option value="sell" {{ ($signal['position'] ?? '') === 'sell' ? 'selected' : '' }}>Sell</option>
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan</label>
            <input data-field="sort_order" name="{{ $fieldPrefix }}[sort_order]" type="number" min="1" value="{{ $signal['sort_order'] ?? ($index + 1) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Entry</label>
            <input data-field="entry_value" name="{{ $fieldPrefix }}[entry_value]" type="text" value="{{ $signal['entry_value'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ $type === 'forex' ? 'TP / Target' : 'Target' }}</label>
            <input data-field="target_value" name="{{ $fieldPrefix }}[target_value]" type="text" value="{{ $signal['target_value'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ $type === 'forex' ? 'SL' : 'Support / Cut Loss' }}</label>
            <input data-field="stop_value" name="{{ $fieldPrefix }}[stop_value]" type="text" value="{{ $signal['stop_value'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
        </div>
        <div class="md:col-span-2 xl:col-span-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
            <textarea data-field="description" name="{{ $fieldPrefix }}[description]" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">{{ $signal['description'] ?? '' }}</textarea>
        </div>
    </div>
</div>
