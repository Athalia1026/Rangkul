@extends('layouts.public')
@section('title', 'Kunjungan Panti - Rangkul')
@section('content')
<main class="visit-page" id="visit-page" data-mode="{{ $mode }}" data-id="{{ request()->route('id') }}" data-organization="{{ request()->route('organization') }}" data-today="{{ now()->toDateString() }}">
    <section class="visit-shell">
        <p id="visit-message" role="status">Memuat informasi kunjungan...</p>
        <div id="visit-content"></div>
    </section>
</main>
@endsection
