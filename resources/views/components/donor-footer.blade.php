<footer class="donor-footer">
    <div class="donor-shell donor-footer-grid">
        <div class="donor-footer-brand">
            <img src="{{ asset('images/logo-light.png') }}" alt="Rangkul.com">
            <p>Rangkul hadir untuk menghubungkan kebaikan melalui platform donasi yang aman, transparan, dan berdampak bagi mereka yang membutuhkan.</p>
            <div class="donor-socials">
                <a href="https://www.instagram.com/" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="18" cy="6" r="1"/></svg></a>
                <a href="https://www.tiktok.com/" aria-label="TikTok" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 3c.4 2 1.8 3.4 4 3.6V10a9 9 0 0 1-4-1.3V16a6 6 0 1 1-6-6v3.5a2.5 2.5 0 1 0 2.5 2.5V3Z"/></svg></a>
                <a href="https://www.facebook.com/" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 22v-9h3l.5-4H14V7c0-1 .3-1.5 1.7-1.5H18V2h-3c-3.3 0-5 2-5 5v2H7v4h3v9Z"/></svg></a>
            </div>
        </div>
        <div><h2>Navigasi</h2><a href="{{ route('donatur.beranda') }}">Beranda</a><a href="{{ route('search') }}">Cari</a><button type="button" data-donor-panel="history">Riwayat</button><a href="{{ route('donatur.dashboard') }}" data-premium-only hidden>Dashboard</a><a href="{{ route('donatur.profil') }}">Profil</a></div>
        <div><h2>Informasi</h2><a href="{{ url('/') }}">Tentang Kami</a><button type="button" data-donor-panel="contact">Kontak</button><button type="button" data-donor-panel="privacy">Kebijakan Privasi</button><button type="button" data-donor-panel="terms">Syarat dan Ketentuan</button></div>
    </div>
    <div class="donor-copyright"><div class="donor-shell">&copy; {{ date('Y') }} Rangkul. Hak cipta dilindungi undang-undang.</div></div>
</footer>
