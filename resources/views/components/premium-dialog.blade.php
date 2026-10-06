@php
    $check = '<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="12" fill="#0b5d36"/><path d="m7 12.5 3.2 3.2L17 9" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
@endphp
<dialog id="premium-dialog" class="premium-dialog" aria-labelledby="premium-dialog-title">
    <button type="button" class="premium-close" aria-label="Tutup" data-premium-close>
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5 5 19"/></svg>
    </button>
    <h2 id="premium-dialog-title">Upgrade ke Premium</h2>
    <p class="premium-subtitle">Nikmati fitur eksklusif untuk pengalaman berdonasi yang lebih optimal.</p>

    <div class="premium-plans">
        <section class="premium-plan">
            <h3>Gratis</h3>
            <p class="premium-plan-desc">Mulai berdonasi dengan mudah.</p>
            <p class="premium-price"><strong>Rp 0</strong><span>/ selamanya</span></p>
            <ul class="premium-features">
                @foreach (['Donasi ke berbagai campaign', 'Jelajahi dan cari campaign', 'Riwayat donasi', 'Ajukan kunjungan ke panti'] as $feature)
                    <li>{!! $check !!}<span>{{ $feature }}</span></li>
                @endforeach
            </ul>
        </section>

        <section class="premium-plan is-premium">
            <h3>Akun Premium Perusahaan</h3>
            <p class="premium-plan-desc">Analitik dan laporan donasi yang lebih lengkap.</p>
            <p class="premium-price"><strong id="premium-price">Rp 1.000.000</strong><span>/ bulan (tagihan tahunan)</span></p>
            <ul class="premium-features two-columns">
                @foreach (['Dashboard Analitik Donasi', 'Monitoring Dampak Donasi', 'Laporan Donasi', 'Statistik Donasi Lengkap'] as $feature)
                    <li>{!! $check !!}<span>{{ $feature }}</span></li>
                @endforeach
            </ul>
            <a href="{{ route('donatur.premium.daftar') }}" id="premium-upgrade" class="premium-upgrade">Upgrade Sekarang</a>
        </section>
    </div>
</dialog>
