@extends('layouts.public')
@section('title', 'Notifikasi - Rangkul')
@section('content')
<main class="donor-updates-wrap" id="notifications-page">
    <section class="donor-updates-card">
        <header class="updates-header"><x-back-link :href="route('donatur.beranda')" /><h1>Notifikasi</h1></header>
        <p id="updates-message" role="status">Memuat notifikasi...</p>
        <div id="notification-list"></div>
        <button type="button" id="notification-more" class="updates-more" hidden>Muat notifikasi sebelumnya</button>
    </section>
</main>
@endsection
