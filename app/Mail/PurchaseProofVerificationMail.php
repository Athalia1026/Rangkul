<?php

namespace App\Mail;

use App\Models\PurchaseProof;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseProofVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public PurchaseProof $proof;

    public function __construct(PurchaseProof $proof)
    {
        $this->proof = $proof;
    }

    public function build()
    {
        $subject = $this->proof->status === 'diterima'
            ? 'Bukti Pengeluaran Anda Telah Disetujui'
            : 'Bukti Pengeluaran Anda Ditolak';

        return $this
            ->subject($subject)
            ->view('emails.purchase-proof-verification');
    }
}
