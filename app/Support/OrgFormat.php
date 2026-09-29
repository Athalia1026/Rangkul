<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Helper tampilan untuk halaman organisasi: format rupiah, tanggal, dan badge status.
 */
class OrgFormat
{
    private const TONES = [
        'green'  => ['badge' => 'bg-[#D7EFE5] text-[#10765B]', 'text' => 'text-emerald-600'],
        'blue'   => ['badge' => 'bg-[#E4E9FA] text-[#5665A6]', 'text' => 'text-blue-600'],
        'yellow' => ['badge' => 'bg-[#FFF2D5] text-gray-800', 'text' => 'text-amber-600'],
        'red'    => ['badge' => 'bg-[#F8DEDE] text-[#B43B3B]', 'text' => 'text-red-500'],
    ];

    private const STATUSES = [
        'campaign' => [
            'menunggu'   => ['Menunggu', 'yellow'],
            'aktif'      => ['Aktif', 'green'],
            'ditolak'    => ['Ditolak', 'red'],
            'disalurkan' => ['Disalurkan', 'blue'],
            'selesai'    => ['Selesai', 'blue'],
        ],
        'donation' => [
            'sudah_bayar' => ['Berhasil', 'green'],
            'belum_bayar' => ['Menunggu', 'yellow'],
            'gagal'       => ['Gagal', 'red'],
        ],
        'disbursement' => [
            'menunggu' => ['Menunggu Verifikasi', 'blue'],
            'diterima' => ['Disetujui', 'green'],
            'ditolak'  => ['Ditolak', 'red'],
        ],
        'proof' => [
            'menunggu' => ['Menunggu Verifikasi', 'blue'],
            'diterima' => ['Disetujui', 'green'],
            'ditolak'  => ['Ditolak', 'red'],
        ],
        'visit' => [
            'terkirim'     => ['Menunggu', 'yellow'],
            'dikonfirmasi' => ['Diterima', 'green'],
            'ditolak'      => ['Ditolak', 'red'],
            'selesai'      => ['Selesai', 'blue'],
        ],
    ];

    public static function rupiah($amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    /** Format tanggal "dd / mm / yyyy" seperti desain halaman organisasi. */
    public static function date($date, string $format = 'd / m / Y'): string
    {
        return $date ? Carbon::parse($date)->format($format) : '-';
    }

    /** Format tanggal panjang berbahasa Indonesia, misalnya "20 Juli 2026". */
    public static function longDate($date): string
    {
        return $date ? Carbon::parse($date)->locale('id')->translatedFormat('j F Y') : '-';
    }

    /** Format tanggal singkat berbahasa Indonesia, misalnya "12 Okt 2026". */
    public static function shortDate($date): string
    {
        return $date ? Carbon::parse($date)->locale('id')->translatedFormat('j M Y') : '-';
    }

    public static function statusLabel(string $type, ?string $status): string
    {
        return self::STATUSES[$type][$status][0] ?? ucfirst((string) $status);
    }

    public static function statusBadge(string $type, ?string $status): string
    {
        return self::TONES[self::tone($type, $status)]['badge'];
    }

    public static function statusText(string $type, ?string $status): string
    {
        return self::TONES[self::tone($type, $status)]['text'];
    }

    public static function storageUrl(?string $path): ?string
    {
        return $path ? asset('storage/' . $path) : null;
    }

    private static function tone(string $type, ?string $status): string
    {
        return self::STATUSES[$type][$status][1] ?? 'blue';
    }
}
