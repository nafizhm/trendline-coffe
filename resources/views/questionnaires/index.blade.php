@php
    $shouldOpenPrintModal = $errors->has('start_date') || $errors->has('end_date');
@endphp

@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kuesioner</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Data Kuesioner</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total respons: {{ $suggestions->count() }}
                    </div>
                    <a
                        href="{{ route('questionnaire-questions.index') }}"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Pertanyaan
                    </a>
                    <button
                        type="button"
                        data-open-modal="printQuestionnaireModal"
                        class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Cetak Laporan
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="questionnairesTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Pengunjung</th>
                                <th class="px-5 py-4">No. Meja</th>
                                <th class="px-5 py-4">Saran</th>
                                <th class="px-5 py-4">Jawaban</th>
                                <th class="px-5 py-4">Dikirim</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($suggestions as $suggestion)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $suggestion->guest_name ?: 'Tamu anonim' }}</div>
                                        <div class="mt-1 text-xs text-slate-500">ID #{{ $suggestion->id }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $suggestion->table_number ?: '-' }}</td>
                                    <td class="px-5 py-4 text-slate-500">
                                        <p class="max-w-md truncate">{{ $suggestion->suggestion }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $suggestion->answers->count() }} jawaban</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $suggestion->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-detail
                                                data-id="{{ $suggestion->id }}"
                                                data-guest-name="{{ $suggestion->guest_name ?: 'Tamu anonim' }}"
                                                data-table-number="{{ $suggestion->table_number ?: '-' }}"
                                                data-suggestion='@json($suggestion->suggestion)'
                                                data-created-at="{{ $suggestion->created_at?->format('d M Y H:i') }}"
                                                data-answers='@json($suggestion->answers->map(fn ($answer) => [
                                                    "question" => $answer->question?->question_text,
                                                    "answer" => $answer->answer_text,
                                                ]))'
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Detail
                                            </button>
                                            <form action="{{ route('questionnaires.destroy', $suggestion) }}" method="POST" onsubmit="return confirm('Hapus data kuesioner ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada data kuesioner.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="printQuestionnaireModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-lg rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Cetak</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Laporan Kuesioner</h3>
                </div>
                <button type="button" data-close-modal="printQuestionnaireModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('questionnaires.print') }}" method="POST" target="_blank" class="space-y-4 px-6 py-6" data-loading-form data-close-on-submit="printQuestionnaireModal">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Periode Awal</label>
                    <input
                        name="start_date"
                        type="date"
                        value="{{ old('start_date') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none"
                        required>
                    @error('start_date')
                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Periode Akhir</label>
                    <input
                        name="end_date"
                        type="date"
                        value="{{ old('end_date') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none"
                        required>
                    @error('end_date')
                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="printQuestionnaireModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Proses</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="questionnaireDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-3xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kuesioner</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900" id="detailTitle">Detail Kuesioner</h3>
                </div>
                <button type="button" data-close-detail class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <div class="space-y-6 px-6 py-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nama Tamu</p>
                        <p id="detailGuestName" class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nomor Meja</p>
                        <p id="detailTableNumber" class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Waktu Kirim</p>
                        <p id="detailCreatedAt" class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Saran</p>
                    <p id="detailSuggestion" class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">-</p>
                </div>

                <div class="rounded-3xl border border-slate-200 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Jawaban Pertanyaan</p>
                    <div id="detailAnswers" class="mt-3 space-y-3 text-sm leading-7 text-slate-700"></div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        const modalElements = {
            printQuestionnaireModal: document.getElementById('printQuestionnaireModal'),
            questionnaireDetailModal: document.getElementById('questionnaireDetailModal'),
        };
        const detailModal = document.getElementById('questionnaireDetailModal');
        const detailTitle = document.getElementById('detailTitle');
        const detailGuestName = document.getElementById('detailGuestName');
        const detailTableNumber = document.getElementById('detailTableNumber');
        const detailCreatedAt = document.getElementById('detailCreatedAt');
        const detailSuggestion = document.getElementById('detailSuggestion');
        const detailAnswers = document.getElementById('detailAnswers');

        function openModal(id) {
            const modal = modalElements[id];
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            const modal = modalElements[id];
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function openDetailModal() {
            openModal('questionnaireDetailModal');
        }

        function closeDetailModal() {
            closeModal('questionnaireDetailModal');
        }

        document.querySelectorAll('[data-open-modal]').forEach((button) => {
            button.addEventListener('click', () => openModal(button.dataset.openModal));
        });

        document.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => closeModal(button.dataset.closeModal));
        });

        document.querySelectorAll('[data-open-detail]').forEach((button) => {
            button.addEventListener('click', () => {
                detailTitle.textContent = `Detail Kuesioner #${button.dataset.id}`;
                detailGuestName.textContent = button.dataset.guestName;
                detailTableNumber.textContent = button.dataset.tableNumber;
                detailCreatedAt.textContent = button.dataset.createdAt || '-';
                detailSuggestion.textContent = JSON.parse(button.dataset.suggestion || '""') || '-';

                detailAnswers.innerHTML = '';

                const answers = JSON.parse(button.dataset.answers || '[]');

                if (!answers.length) {
                    const emptyState = document.createElement('p');
                    emptyState.className = 'text-slate-500';
                    emptyState.textContent = 'Belum ada jawaban pertanyaan.';
                    detailAnswers.appendChild(emptyState);
                } else {
                    answers.forEach((answer) => {
                        const item = document.createElement('div');
                        item.className = 'rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3';
                        item.textContent = `${answer.question ? `${answer.question}: ` : ''}${answer.answer || '-'}`;
                        detailAnswers.appendChild(item);
                    });
                }

                openDetailModal();
            });
        });

        document.querySelectorAll('[data-close-detail]').forEach((button) => {
            button.addEventListener('click', closeDetailModal);
        });

        Object.values(modalElements).forEach((modal) => {
            if (!modal) return;
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        document.querySelectorAll('[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const submitButton = form.querySelector('[data-submit-button]');
                const label = form.querySelector('.submit-label');
                const spinner = form.querySelector('.submit-spinner');
                const modalId = form.dataset.closeOnSubmit;

                if (!submitButton || !label || !spinner) return;

                submitButton.disabled = true;
                submitButton.classList.add('cursor-not-allowed', 'opacity-80');
                label.classList.add('hidden');
                spinner.classList.remove('hidden');
                spinner.classList.add('inline-flex');

                if (modalId) {
                    closeModal(modalId);
                }
            });
        });

        if (window.jQuery) {
            $('#questionnairesTable').DataTable({
                pageLength: 10,
                order: [[4, 'desc']],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Belum ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    }
                }
            });
        }

        @if ($shouldOpenPrintModal)
            openModal('printQuestionnaireModal');
        @endif
    </script>
@endsection
