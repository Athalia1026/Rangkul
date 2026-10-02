@extends('layouts.public')
@section('title', 'Detail Penyaluran - Rangkul')
@section('content')
<main class="donor-updates-wrap" id="distribution-page" data-id="{{ request()->route('id') }}">
    <section class="donor-updates-card distribution-card">
        <a class="updates-back" href="{{ route('donatur.riwayat') }}">← Kembali</a>
        <p id="updates-message" role="status">Memuat detail penyaluran...</p>
        <div id="distribution-content"></div>
        <button type="button" id="distribution-preview" class="checkout-secondary" hidden>Lihat Contoh Detail Penyaluran</button>
    </section>
</main>
@endsection
