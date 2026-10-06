@extends('layouts.public')
@section('title', 'Ubah Password - Rangkul')
@section('content')
@php
    $eye = '<svg class="icon-show" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5C6.5 5 2.7 8.6 1.3 12c1.4 3.4 5.2 7 10.7 7s9.3-3.6 10.7-7C21.3 8.6 17.5 5 12 5Zm0 11.2a4.2 4.2 0 1 1 0-8.4 4.2 4.2 0 0 1 0 8.4Zm0-6.4a2.2 2.2 0 1 0 0 4.4 2.2 2.2 0 0 0 0-4.4Z"/></svg>'
        . '<svg class="icon-hide" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 3l18 18M10.6 6.1A10.8 10.8 0 0 1 12 6c5 0 8.4 3.3 9.7 6a11.6 11.6 0 0 1-2.6 3.5M6.6 6.6A11.4 11.4 0 0 0 2.3 12c1.3 2.7 4.7 6 9.7 6 1.6 0 3-.3 4.3-.9M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>';
@endphp
<main id="password-page" class="password-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <section class="password-card">
        <a href="{{ route('donatur.profil') }}" class="password-back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Ubah Password</h1>
        <p class="password-intro">Perbarui kata sandi Anda untuk menjaga keamanan akun.</p>

        <form id="password-form" novalidate>
            <div class="password-field">
                <label for="current_password">Password Saat Ini</label>
                <div class="password-input">
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan password anda" aria-describedby="current_password-error" required>
                    <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">{!! $eye !!}</button>
                </div>
                <div class="password-meta">
                    <p id="current_password-error" class="password-error" role="alert"></p>
                    <a href="{{ route('password.request') }}" class="password-forgot">Lupa Password?</a>
                </div>
            </div>

            <div class="password-field">
                <label for="password">Password Baru</label>
                <div class="password-input">
                    <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Masukkan password baru anda" aria-describedby="password-hint password-error" required>
                    <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">{!! $eye !!}</button>
                </div>
                <p id="password-error" class="password-error" role="alert"></p>
                <p id="password-hint" class="password-hint">Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.</p>
            </div>

            <div class="password-field">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <div class="password-input">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password anda" aria-describedby="password_confirmation-error" required>
                    <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">{!! $eye !!}</button>
                </div>
                <p id="password_confirmation-error" class="password-error" role="alert"></p>
            </div>

            <p id="password-status" class="password-status" role="status"></p>
            <div class="password-actions">
                <button type="submit" class="profile-btn primary">Simpan Perubahan</button>
                <a href="{{ route('donatur.profil') }}" class="profile-btn secondary">Batalkan</a>
            </div>
        </form>
    </section>
</main>
@endsection
