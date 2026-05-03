@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('question_id');
    $editingQuestion = $editingId ? $questions->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingQuestion;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kuesioner</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Pertanyaan Kuesioner</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $questions->count() }}
                    </div>
                    <button
                        type="button"
                        data-open-modal="createQuestionModal"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Pertanyaan
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="questionnaireQuestionsTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Urutan</th>
                                <th class="px-5 py-4">Pertanyaan</th>
                                <th class="px-5 py-4">Placeholder</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Dibuat</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($questions as $question)
                                <tr>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $question->sort_order }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $question->question_text }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $question->placeholder ?: '-' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $question->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $question->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $question->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $question->id }}"
                                                data-question-text="{{ $question->question_text }}"
                                                data-placeholder="{{ $question->placeholder }}"
                                                data-sort-order="{{ $question->sort_order }}"
                                                data-is-active="{{ $question->is_active ? '1' : '0' }}"
                                                data-update-url="{{ route('questionnaire-questions.update', $question) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('questionnaire-questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan kuesioner ini?')">
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
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada pertanyaan kuesioner.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createQuestionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kuesioner</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Pertanyaan</h3>
                </div>
                <button type="button" data-close-modal="createQuestionModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('questionnaire-questions.store') }}" method="POST" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Pertanyaan</label>
                    <textarea name="question_text" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ $formMode === 'create' ? old('question_text') : '' }}</textarea>
                    @if ($formMode === 'create')
                        @error('question_text')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Placeholder</label>
                    <input name="placeholder" type="text" value="{{ $formMode === 'create' ? old('placeholder') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('placeholder')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                        <input name="sort_order" type="number" min="1" value="{{ $formMode === 'create' ? old('sort_order', $questions->count() + 1) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('sort_order')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 md:self-end">
                        <input name="is_active" type="checkbox" value="1" {{ $formMode === 'create' ? (old('is_active', true) ? 'checked' : '') : 'checked' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                        Aktifkan pertanyaan
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createQuestionModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan Pertanyaan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editQuestionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kuesioner</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Pertanyaan</h3>
                </div>
                <button type="button" data-close-modal="editQuestionModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingQuestion ? route('questionnaire-questions.update', $editingQuestion) : '#' }}" method="POST" class="space-y-4 px-6 py-6" id="editQuestionForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="question_id" id="edit_question_id" value="{{ $editingQuestion?->id }}">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Pertanyaan</label>
                    <textarea id="edit_question_text" name="question_text" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ $formMode === 'edit' ? old('question_text', $editingQuestion?->question_text) : '' }}</textarea>
                    @if ($formMode === 'edit')
                        @error('question_text')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Placeholder</label>
                    <input id="edit_placeholder" name="placeholder" type="text" value="{{ $formMode === 'edit' ? old('placeholder', $editingQuestion?->placeholder) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('placeholder')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                        <input id="edit_sort_order" name="sort_order" type="number" min="1" value="{{ $formMode === 'edit' ? old('sort_order', $editingQuestion?->sort_order) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('sort_order')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 md:self-end">
                        <input id="edit_is_active" name="is_active" type="checkbox" value="1" {{ $formMode === 'edit' ? (old('is_active', $editingQuestion?->is_active) ? 'checked' : '') : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                        Aktifkan pertanyaan
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editQuestionModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan Perubahan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        const modalElements = {
            createQuestionModal: document.getElementById('createQuestionModal'),
            editQuestionModal: document.getElementById('editQuestionModal'),
        };
        const editQuestionForm = document.getElementById('editQuestionForm');

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

        document.querySelectorAll('[data-open-modal]').forEach((button) => {
            button.addEventListener('click', () => openModal(button.dataset.openModal));
        });

        document.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => closeModal(button.dataset.closeModal));
        });

        Object.values(modalElements).forEach((modal) => {
            if (!modal) return;
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        document.querySelectorAll('[data-open-edit-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('edit_question_id').value = button.dataset.id;
                document.getElementById('edit_question_text').value = button.dataset.questionText;
                document.getElementById('edit_placeholder').value = button.dataset.placeholder;
                document.getElementById('edit_sort_order').value = button.dataset.sortOrder;
                document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';
                editQuestionForm.action = button.dataset.updateUrl;
                openModal('editQuestionModal');
            });
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

        if (window.jQuery) {
            $('#questionnaireQuestionsTable').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
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

        @if ($shouldOpenCreateModal)
            openModal('createQuestionModal');
        @endif

        @if ($shouldOpenEditModal)
            editQuestionForm.action = '{{ $editingQuestion ? route('questionnaire-questions.update', $editingQuestion) : '#' }}';
            openModal('editQuestionModal');
        @endif
    </script>
@endsection
