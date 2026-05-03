@extends('layouts.feedback', ['title' => 'Terima Kasih | Trendline Feedback'])

@section('content')
    <div class="header">
        <div class="logo-card">
            <img src="{{ asset('images/trendline-logo.png') }}" alt="Trendline Coffee">
        </div>
        <div class="header-tagline">Feedback Anda Sudah Terkirim</div>
        <div class="header-sub">Terima kasih sudah meluangkan waktu untuk membantu Trendline jadi lebih baik.</div>
    </div>

    <div class="thankyou">
        <span class="ty-icon">Terima kasih</span>
        <div class="ty-title">Terima Kasih!</div>
        <div class="ty-divider"></div>
        <p class="ty-sub">
            Masukan Anda sudah kami terima dengan baik.<br>
            Tim kami akan menggunakan feedback ini untuk terus meningkatkan pengalaman di Trendline.
        </p>
        <a class="reset-btn" href="{{ route('kuesioner.index') }}">Kembali ke Kuesioner</a>
    </div>
@endsection
