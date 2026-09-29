<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDisbursement;
use App\Models\Notification;
use App\Models\Organization;
use App\Models\PurchaseProof;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Visit;
use App\Support\OrgFormat;
use Illuminate\Support\Facades\Log;

/**
 * Membuat notifikasi in-app (tabel notifications) untuk donatur, organisasi, dan admin.
 *
 * Setiap method dipanggil setelah event bisnis terjadi. Kegagalan membuat notifikasi
 * hanya dicatat ke log dan tidak pernah menggagalkan proses utama.
 */
class NotificationService
{
    public const REFERENCE_TYPES = [
        'donation', 'campaign', 'visit', 'fund_disbursement',
        'purchase_proof', 'organization', 'subscription', 'report',
    ];

    // =====================================================================
    // DONASI
    // =====================================================================

    public function donationPaid(Donation $donation): void
    {
        $donation->loadMissing(['campaign.organization', 'donor']);
        $judul = $donation->campaign?->judul ?? 'kampanye';
        $nominal = OrgFormat::rupiah($donation->nominal);

        $this->send(
            $donation->donor?->user_id,
            'Donasi Berhasil',
            "Terima kasih! Donasi Anda sebesar {$nominal} untuk kampanye \"{$judul}\" telah kami terima.",
            'donation',
            $donation->id
        );

        $this->send(
            $donation->campaign?->organization?->user_id,
            'Donasi Baru Masuk',
            "Kampanye \"{$judul}\" menerima donasi sebesar {$nominal}.",
            'donation',
            $donation->id
        );
    }

    public function donationFailed(Donation $donation): void
    {
        $donation->loadMissing(['campaign', 'donor']);

        $this->send(
            $donation->donor?->user_id,
            'Donasi Gagal',
            'Pembayaran donasi Anda sebesar ' . OrgFormat::rupiah($donation->nominal)
                . ' untuk kampanye "' . ($donation->campaign?->judul ?? '-') . '" gagal atau kedaluwarsa.',
            'donation',
            $donation->id
        );
    }

    // =====================================================================
    // KAMPANYE
    // =====================================================================

    public function campaignSubmitted(Campaign $campaign): void
    {
        $campaign->loadMissing('organization');

        $this->sendToAdmins(
            'Kampanye Baru Menunggu Verifikasi',
            ($campaign->organization?->nama_lembaga ?? 'Organisasi') . " mengajukan kampanye \"{$campaign->judul}\".",
            'campaign',
            $campaign->id
        );
    }

    public function campaignVerified(Campaign $campaign): void
    {
        $campaign->loadMissing('organization');
        $approved = $campaign->status === 'aktif';

        $this->send(
            $campaign->organization?->user_id,
            $approved ? 'Kampanye Berhasil Diterbitkan' : 'Kampanye Ditolak',
            $approved
                ? "Kampanye Anda berjudul \"{$campaign->judul}\" telah disetujui dan sudah tayang."
                : "Kampanye \"{$campaign->judul}\" ditolak. Alasan: " . ($campaign->alasan_tolak ?: '-'),
            'campaign',
            $campaign->id
        );
    }

    // =====================================================================
    // KUNJUNGAN
    // =====================================================================

    public function visitRequested(Visit $visit): void
    {
        $visit->loadMissing(['organization', 'donor.user']);

        $this->send(
            $visit->organization?->user_id,
            'Jadwal Kunjungan Baru Masuk',
            ($visit->donor?->user?->nama ?? 'Donatur') . ' mengajukan kunjungan pada '
                . OrgFormat::longDate($visit->tanggal_kunjungan) . " untuk {$visit->pengunjung} orang.",
            'visit',
            $visit->id
        );
    }

    public function visitUpdated(Visit $visit): void
    {
        $visit->loadMissing(['organization', 'donor.user']);

        $this->send(
            $visit->organization?->user_id,
            'Jadwal Kunjungan Diubah',
            ($visit->donor?->user?->nama ?? 'Donatur') . ' mengubah pengajuan kunjungan menjadi '
                . OrgFormat::longDate($visit->tanggal_kunjungan) . '.',
            'visit',
            $visit->id
        );
    }

    public function visitResponded(Visit $visit): void
    {
        $visit->loadMissing(['organization', 'donor']);
        $accepted = $visit->status === 'dikonfirmasi';
        $organizationName = $visit->organization?->nama_lembaga ?? 'Organisasi';

        $this->send(
            $visit->donor?->user_id,
            $accepted ? 'Kunjungan Diterima' : 'Kunjungan Ditolak',
            ($accepted
                ? "{$organizationName} menerima kunjungan Anda pada " . OrgFormat::longDate($visit->tanggal_kunjungan) . '.'
                : "{$organizationName} menolak pengajuan kunjungan Anda.")
                . ($visit->pesan_organisasi ? " Pesan: {$visit->pesan_organisasi}" : ''),
            'visit',
            $visit->id
        );
    }

    public function visitCompleted(Visit $visit): void
    {
        $visit->loadMissing(['organization', 'donor.user']);

        $this->send(
            $visit->organization?->user_id,
            'Kunjungan Selesai',
            ($visit->donor?->user?->nama ?? 'Donatur') . ' telah mengunggah dokumentasi kunjungan.',
            'visit',
            $visit->id
        );
    }

    // =====================================================================
    // PENCAIRAN DANA
    // =====================================================================

    public function disbursementRequested(FundDisbursement $disbursement): void
    {
        $disbursement->loadMissing('campaign.organization');

        $this->sendToAdmins(
            'Pengajuan Pencairan Dana Baru',
            ($disbursement->campaign?->organization?->nama_lembaga ?? 'Organisasi') . ' mengajukan pencairan '
                . OrgFormat::rupiah($disbursement->nominal_diajukan) . ' untuk kampanye "'
                . ($disbursement->campaign?->judul ?? '-') . '".',
            'fund_disbursement',
            $disbursement->id
        );
    }

    public function disbursementVerified(FundDisbursement $disbursement): void
    {
        $disbursement->loadMissing('campaign.organization');
        $approved = $disbursement->status === 'diterima';

        $this->send(
            $disbursement->campaign?->organization?->user_id,
            $approved ? 'Pengajuan Pencairan Dana Disetujui' : 'Pengajuan Pencairan Dana Ditolak',
            $approved
                ? 'Pencairan "' . $disbursement->alokasi_dana . '" sebesar '
                    . OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan)
                    . ' disetujui dan menunggu transfer oleh admin.'
                : 'Pencairan "' . $disbursement->alokasi_dana . '" ditolak. Alasan: ' . ($disbursement->alasan_tolak ?: '-'),
            'fund_disbursement',
            $disbursement->id
        );
    }

    public function disbursementTransferred(FundDisbursement $disbursement): void
    {
        $disbursement->loadMissing('campaign.organization');

        $this->send(
            $disbursement->campaign?->organization?->user_id,
            'Dana Berhasil Ditransfer',
            'Dana sebesar ' . OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan)
                . ' untuk "' . $disbursement->alokasi_dana . '" telah ditransfer ke rekening Anda. Jangan lupa unggah bukti penyaluran.',
            'fund_disbursement',
            $disbursement->id
        );
    }

    // =====================================================================
    // BUKTI PENYALURAN
    // =====================================================================

    public function proofUploaded(PurchaseProof $proof): void
    {
        $proof->loadMissing('fundDisbursement.campaign.organization');
        $campaign = $proof->fundDisbursement?->campaign;

        $this->sendToAdmins(
            'Bukti Penyaluran Baru',
            ($campaign?->organization?->nama_lembaga ?? 'Organisasi') . ' mengunggah bukti penyaluran '
                . OrgFormat::rupiah($proof->nominal) . ' untuk kampanye "' . ($campaign?->judul ?? '-') . '".',
            'purchase_proof',
            $proof->id
        );
    }

    public function proofVerified(PurchaseProof $proof): void
    {
        $proof->loadMissing('fundDisbursement.campaign.organization');
        $approved = $proof->status === 'diterima';
        $alokasi = $proof->fundDisbursement?->alokasi_dana ?? '-';

        $this->send(
            $proof->fundDisbursement?->campaign?->organization?->user_id,
            $approved ? 'Bukti Penyaluran Disetujui' : 'Bukti Penyaluran Ditolak',
            $approved
                ? "Bukti penyaluran untuk \"{$alokasi}\" telah diverifikasi admin."
                : "Bukti penyaluran untuk \"{$alokasi}\" ditolak. Alasan: " . ($proof->alasan_tolak ?: '-'),
            'purchase_proof',
            $proof->id
        );
    }

    // =====================================================================
    // ORGANISASI
    // =====================================================================

    public function organizationRegistered(Organization $organization, bool $isResubmission = false): void
    {
        $this->sendToAdmins(
            $isResubmission ? 'Pendaftaran Ulang Organisasi' : 'Pendaftaran Organisasi Baru',
            "{$organization->nama_lembaga} ({$organization->kota}) "
                . ($isResubmission ? 'mengirim ulang dokumen pendaftaran.' : 'mendaftar dan menunggu verifikasi dokumen.'),
            'organization',
            $organization->id
        );
    }

    public function organizationVerified(Organization $organization): void
    {
        $approved = $organization->verification_status === 'disetujui';

        $this->send(
            $organization->user_id,
            $approved ? 'Organisasi Terverifikasi' : 'Verifikasi Organisasi Ditolak',
            $approved
                ? 'Selamat! Organisasi Anda telah diverifikasi. Anda sekarang dapat membuat kampanye.'
                : 'Pendaftaran organisasi Anda ditolak. Silakan perbaiki dokumen dan ajukan ulang.',
            'organization',
            $organization->id
        );
    }

    // =====================================================================
    // LANGGANAN PREMIUM
    // =====================================================================

    public function subscriptionActivated(Subscription $subscription): void
    {
        $subscription->loadMissing('company.donor');

        $this->send(
            $subscription->company?->donor?->user_id,
            'Langganan Premium Aktif',
            'Langganan premium Anda aktif hingga ' . OrgFormat::longDate($subscription->expired_at) . '.',
            'subscription',
            $subscription->id
        );
    }

    public function subscriptionExpiring(Subscription $subscription): void
    {
        $subscription->loadMissing('company.donor');

        $this->send(
            $subscription->company?->donor?->user_id,
            'Langganan Premium Segera Berakhir',
            'Langganan premium Anda akan berakhir pada ' . OrgFormat::longDate($subscription->expired_at) . '. Perpanjang agar laporan dampak tetap tersedia.',
            'subscription',
            $subscription->id
        );
    }

    // =====================================================================
    // DASAR
    // =====================================================================

    public function send(?string $userId, string $judul, string $deskripsi, string $referenceType, string $referenceId): ?Notification
    {
        if (!$userId) {
            return null;
        }

        try {
            return Notification::create([
                'user_id'        => $userId,
                'judul'          => $judul,
                'deskripsi'      => $deskripsi,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'is_read'        => false,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Gagal membuat notifikasi', [
                'user_id' => $userId,
                'judul'   => $judul,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /** Kirim notifikasi ke semua admin yang akunnya aktif. */
    public function sendToAdmins(string $judul, string $deskripsi, string $referenceType, string $referenceId): void
    {
        User::where('account_type', 'admin')
            ->where('status', 'aktif')
            ->whereDoesntHave('adminProfile', fn ($q) => $q->where('status_akun', '!=', 'aktif'))
            ->pluck('id')
            ->each(fn ($userId) => $this->send($userId, $judul, $deskripsi, $referenceType, $referenceId));
    }
}
