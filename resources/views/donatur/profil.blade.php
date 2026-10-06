@extends('layouts.public')
@section('title', 'Profil Saya - Rangkul')
@section('content')
<main id="profile-page" class="profile-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <p id="profile-status" class="profile-status" role="status">Memuat profil...</p>

    <form id="profile-form" class="profile-card" novalidate hidden>
        <div class="profile-photo">
            <label class="profile-avatar" for="profile-photo-input">
                <img id="profile-avatar-image" alt="Foto profil" hidden>
                <span id="profile-avatar-initials" aria-hidden="true">D</span>
                <span class="profile-avatar-badge" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9"/><circle cx="12" cy="13" r="3"/><path d="M19 15v6m-3-3h6"/></svg>
                </span>
                <span class="sr-only">Ganti foto profil</span>
            </label>
            <input id="profile-photo-input" type="file" accept="image/jpeg,image/png" hidden>
            <h1 id="profile-name-heading"></h1>
            <p id="profile-photo-error" class="profile-error" role="alert"></p>
        </div>

        <div class="profile-fields">
            <label><span id="profile-name-label">Nama</span><input name="nama" type="text" autocomplete="name" maxlength="255" required></label>
            <label>Alamat Email<input name="email" type="email" readonly aria-readonly="true" tabindex="-1" title="Email tidak dapat diubah"></label>
            <label>Nomor Telepon<input name="no_telp" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" required></label>
            <label>Kota<input name="kota" type="text" autocomplete="address-level2" maxlength="255" required></label>
        </div>

        <div class="profile-footer">
            <p id="profile-form-message" class="profile-message" role="status"></p>
            <div class="profile-actions">
                <button type="button" id="profile-cancel" class="profile-btn secondary" hidden>Batalkan</button>
                <button type="submit" id="profile-save" class="profile-btn primary" disabled>Simpan Perubahan</button>
            </div>
        </div>
    </form>

    <div id="profile-shortcuts" class="profile-shortcuts" hidden>
        <section class="profile-shortcut">
            <div class="profile-shortcut-head">
                <svg width="30" height="34" viewBox="0 0 24 28" aria-hidden="true"><path d="M7 11V7a5 5 0 0 1 10 0v4" fill="none" stroke="currentColor" stroke-width="2.6"/><rect x="2" y="11" width="20" height="16" rx="1.5" fill="currentColor"/><circle cx="12" cy="19" r="2" fill="#fff"/></svg>
                <div><h2>Ubah Password</h2><p>Perbarui kata sandi akun Anda.</p></div>
            </div>
            <a href="{{ route('donatur.password') }}" class="profile-shortcut-btn primary">Ubah Password Sekarang</a>
        </section>
        <section class="profile-shortcut">
            <div class="profile-shortcut-head">
                <svg width="30" height="34" viewBox="0 0 24 28" aria-hidden="true"><path d="M2 3h20v22H2z" fill="currentColor"/><path d="M6.5 14h11m-4-4 4 4-4 4" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div><h2>Keluar dari Akun</h2><p>Keluar dari akun Anda dengan aman.</p></div>
            </div>
            <button type="button" id="profile-logout" class="profile-shortcut-btn soft">
                Keluar
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            </button>
        </section>
    </div>
</main>

@endsection
