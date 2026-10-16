@props(['href', 'light' => false])

{{-- Tautan "← Kembali" yang seragam untuk halaman donatur. Pakai :light="true" di atas gambar/latar gelap. --}}
<a href="{{ $href }}" {{ $attributes->class([
    'group inline-flex items-center gap-2 text-[16px] font-normal transition-colors',
    'text-gray-800 hover:text-[#05522d]' => ! $light,
    'text-white/95 hover:text-white drop-shadow-md' => $light,
]) }}>
    <svg class="w-5 h-5 shrink-0 transition-transform group-hover:-translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M19 12H5m7 7-7-7 7-7" />
    </svg>
    <span>{{ $slot->isEmpty() ? 'Kembali' : $slot }}</span>
</a>
