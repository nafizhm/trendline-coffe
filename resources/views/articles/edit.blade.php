@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Artikel</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Edit Artikel</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Perbarui konten artikel di halaman ini tanpa modal agar area editor lebih luas dan nyaman dipakai.</p>
                </div>
                <a href="{{ route('articles.index') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </section>

        <form action="{{ route('articles.update', $article) }}" method="POST" class="space-y-6" data-loading-form>
            @csrf
            @method('PUT')

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                @include('articles._form', ['article' => $article])

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('articles.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan Perubahan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </section>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
        if (window.jQuery && $.fn.summernote) {
            $('.js-summernote').summernote({
                placeholder: 'Tulis isi artikel di sini...',
                height: 360,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table', 'hr']],
                    ['view', ['codeview']]
                ]
            });
        }

        document.querySelectorAll('[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                if (window.jQuery && $.fn.summernote) {
                    $('.js-summernote').each(function () {
                        $(this).val($(this).summernote('code'));
                    });
                }

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
    </script>
@endsection
