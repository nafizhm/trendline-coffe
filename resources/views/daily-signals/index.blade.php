@extends('layouts.app')

@php
    $pageTitle = $type === 'forex' ? 'Sinyal Harian Forex' : 'Sinyal Harian Saham';
    $makassarNow = now('Asia/Makassar');
    $oldSignals = collect(old('signals', []));
    $signalsData = $oldSignals->where('type', $type)->values();

    if ($signalsData->isEmpty()) {
        $signalsData = $signals->map(fn ($signal) => [
            'type' => $signal->type,
            'symbol' => $signal->symbol,
            'pair_name' => $signal->pair_name,
            'position' => $signal->position,
            'signal_date' => optional($signal->signal_date)->format('Y-m-d'),
            'signal_time' => $signal->signal_time,
            'entry_value' => $signal->entry_value,
            'target_value' => $signal->target_value,
            'stop_value' => $signal->stop_value,
            'description' => $signal->description,
            'sort_order' => $signal->sort_order,
        ]);
    }

    if ($signalsData->isEmpty()) {
        $signalsData = collect([[
            'type' => $type,
            'symbol' => '',
            'pair_name' => '',
            'position' => 'buy',
            'signal_date' => $makassarNow->format('Y-m-d'),
            'signal_time' => $makassarNow->format('H:i'),
            'entry_value' => '',
            'target_value' => '',
            'stop_value' => '',
            'description' => '',
            'sort_order' => 1,
        ]]);
    }
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Sinyal</p>
            <h2 class="mt-2 text-3xl font-black text-slate-900">{{ $pageTitle }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                Input sinyal {{ $type === 'forex' ? 'Forex' : 'Saham' }} dalam satu halaman. Pilih posisi Buy atau Sell, lalu simpan dari tombol di bagian bawah.
            </p>
        </section>

        @if ($errors->any())
            <section class="rounded-[28px] border border-rose-200 bg-rose-50 p-6 shadow-sm">
                <h3 class="text-lg font-black text-rose-700">Simpan gagal</h3>
                <div class="mt-3 space-y-2 text-sm text-rose-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </section>
        @endif

        <form action="{{ route('daily-signals.update', ['type' => $type]) }}" method="POST" class="space-y-6" data-loading-form>
            @csrf
            @method('PUT')

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">{{ $type === 'forex' ? 'Forex' : 'Saham' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $type === 'forex' ? 'Contoh: XAU/USD, EUR/USD, GBP/USD.' : 'Contoh: BBRI, TLKM, BMRI.' }}</p>
                    </div>
                    <button type="button" data-add-signal="{{ $type }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Tambah {{ $type === 'forex' ? 'Forex' : 'Saham' }}
                    </button>
                </div>
                <div id="{{ $type }}-signals" class="space-y-4" data-signal-group="{{ $type }}">
                    @foreach ($signalsData as $index => $signal)
                        @include('daily-signals.partials.signal-card', ['signal' => $signal, 'type' => $type, 'index' => $index])
                    @endforeach
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                    <span class="submit-label">Simpan</span>
                    <span class="submit-spinner hidden items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <template id="signal-card-template">
        @include('daily-signals.partials.signal-card', [
            'signal' => [
                'type' => '__TYPE__',
                'symbol' => '',
                'pair_name' => '',
                'position' => 'buy',
                'signal_date' => '__NOW_DATE__',
                'signal_time' => '__NOW_TIME__',
                'entry_value' => '',
                'target_value' => '',
                'stop_value' => '',
                'description' => '',
                'sort_order' => '__INDEX__',
            ],
            'type' => '__TYPE__',
            'index' => '__INDEX__',
        ])
    </template>

    <script>
        const signalTimeZone = 'Asia/Makassar';

        function getCurrentSignalDate() {
            return new Intl.DateTimeFormat('en-CA', {
                timeZone: signalTimeZone,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            }).format(new Date());
        }

        function getCurrentSignalTime() {
            return new Intl.DateTimeFormat('en-GB', {
                timeZone: signalTimeZone,
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
            }).format(new Date());
        }

        function refreshSignalIndexes(type) {
            const wrapper = document.querySelector(`[data-signal-group="${type}"]`);
            if (!wrapper) return;

            wrapper.querySelectorAll('[data-signal-card]').forEach((card, index) => {
                card.querySelectorAll('[data-field]').forEach((field) => {
                    const key = field.dataset.field;
                    field.name = `signals[${type}-${index}][${key}]`;
                });
            });
        }

        document.querySelectorAll('[data-add-signal]').forEach((button) => {
            button.addEventListener('click', () => {
                const type = button.dataset.addSignal;
                const wrapper = document.querySelector(`[data-signal-group="${type}"]`);
                const template = document.getElementById('signal-card-template').innerHTML
                    .replaceAll('__TYPE__', type)
                    .replaceAll('__NOW_DATE__', getCurrentSignalDate())
                    .replaceAll('__NOW_TIME__', getCurrentSignalTime())
                    .replaceAll('__INDEX__', wrapper.querySelectorAll('[data-signal-card]').length + 1);

                wrapper.insertAdjacentHTML('beforeend', template);
                refreshSignalIndexes(type);
            });
        });

        document.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-signal]');
            if (!removeButton) return;

            const card = removeButton.closest('[data-signal-card]');
            const wrapper = card.parentElement;
            card.remove();

            if (!wrapper.querySelector('[data-signal-card]')) {
                const type = wrapper.dataset.signalGroup;
                const template = document.getElementById('signal-card-template').innerHTML
                    .replaceAll('__TYPE__', type)
                    .replaceAll('__NOW_DATE__', getCurrentSignalDate())
                    .replaceAll('__NOW_TIME__', getCurrentSignalTime())
                    .replaceAll('__INDEX__', 1);
                wrapper.insertAdjacentHTML('beforeend', template);
            }

            refreshSignalIndexes(wrapper.dataset.signalGroup);
        });

        document.querySelectorAll('[data-signal-group]').forEach((wrapper) => {
            refreshSignalIndexes(wrapper.dataset.signalGroup);
        });

        document.querySelectorAll('[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const submitButton = form.querySelector('[data-submit-button]');
                const label = form.querySelector('.submit-label');
                const spinner = form.querySelector('.submit-spinner');

                if (!submitButton || !label || !spinner) return;

                submitButton.disabled = true;
                submitButton.classList.add('cursor-not-allowed', 'opacity-80');
                label.classList.add('hidden');
                spinner.classList.remove('hidden');
                spinner.classList.add('inline-flex');
            });
        });

        @if (session('status'))
            alert(@json(session('status')));
        @endif
    </script>
@endsection
