@props(['campaign', 'priority' => false])
@php
    $cover = $campaign->foto_cover;
    $image = $cover ? (filter_var($cover, FILTER_VALIDATE_URL) ? $cover : asset('storage/' . ltrim($cover, '/'))) : asset('images/hero/hero_children.jpg');
@endphp
<a href="{{ route('campaign.detail', $campaign->id) }}" class="donor-card">
    <div class="donor-card-image">
        <img src="{{ $image }}" alt="{{ $campaign->judul }}" loading="lazy">
        @if ($priority)
            <span class="donor-deadline"><svg width="17" height="17" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="currentColor"/><path d="M12 5v7l4 4" fill="none" stroke="#f5dcdc" stroke-width="2" stroke-linecap="round"/></svg> SISA {{ $campaign->sisa_hari }} HARI</span>
        @endif
    </div>
    <div class="donor-card-content">
        <p class="donor-card-organization">{{ $campaign->organization?->nama_lembaga ?? 'Organisasi sosial' }}</p>
        <h3>{{ $campaign->judul }}</h3>
        <p class="donor-card-amount">Terkumpul <strong>Rp {{ number_format($campaign->total_terkumpul, 0, ',', '.') }}</strong></p>
        <div class="donor-progress" role="progressbar" aria-label="Dana terkumpul" aria-valuenow="{{ $campaign->progress }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $campaign->progress }}%"></span></div>
    </div>
</a>
