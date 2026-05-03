@extends('layouts.feedback', ['title' => 'Trendline Feedback'])

@section('content')
    @php
        $totalSections = $questions->count();
    @endphp

    <div class="header">
        <div class="logo-card">
            <img src="{{ asset('images/trendline-logo.png') }}" alt="Trendline Coffee">
        </div>
        <div class="header-tagline">Bagaimana Kunjungan Anda Hari Ini?</div>
        <div class="header-sub">Luangkan 2 menit dan ceritakan pengalaman Anda di Trendline Coffee.</div>
    </div>

    <div class="progress-wrap">
        <div class="progress-info">
            <span class="progress-label">Progress</span>
            <span class="progress-count" id="progCount">0 dari {{ $totalSections }} dijawab</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progFill"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('kuesioner.store') }}" novalidate>
        @csrf

        @foreach ($questions as $index => $question)
            <section class="q-card" style="animation-delay: {{ number_format($index * 0.05, 2) }}s">
                <div class="q-number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }} - Pertanyaan</div>
                <div class="q-text">{{ $question->question_text }}</div>
                <textarea
                    class="field-textarea js-progress-field @error('answers.'.$question->id) field-error @enderror"
                    id="answers_{{ $question->id }}"
                    name="answers[{{ $question->id }}]"
                    placeholder="{{ $question->placeholder ?: 'Ceritakan pengalaman Anda dengan singkat dan jujur...' }}"
                    required
                >{{ old('answers.'.$question->id) }}</textarea>
                @error('answers.'.$question->id)
                    <div class="error-text">{{ $message }}</div>
                @else
                    <div class="helper-text">Bagian ini wajib diisi sebelum feedback dikirim.</div>
                @enderror
            </section>
        @endforeach

        <div class="submit-wrap">
            <button class="submit-btn" type="submit">Kirim Feedback</button>
        </div>
    </form>

    <p class="footer-note">Terima kasih telah memilih Trendline. Kami selalu berusaha menyajikan yang terbaik.</p>
@endsection

@push('scripts')
    <script>
        (() => {
            const fields = Array.from(document.querySelectorAll('.js-progress-field'));
            const countNode = document.getElementById('progCount');
            const fillNode = document.getElementById('progFill');
            const total = fields.length;

            if (!countNode || !fillNode || total === 0) {
                return;
            }

            const updateProgress = () => {
                const filled = fields.filter((field) => field.value.trim().length > 0).length;
                const percent = Math.round((filled / total) * 100);

                fillNode.style.width = `${percent}%`;
                countNode.textContent = `${filled} dari ${total} dijawab`;
            };

            fields.forEach((field) => {
                field.addEventListener('input', updateProgress);
            });

            updateProgress();

            const firstErrorField = document.querySelector('.field-error');
            if (firstErrorField) {
                requestAnimationFrame(() => {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                });
            }
        })();
    </script>
@endpush
