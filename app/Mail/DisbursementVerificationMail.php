<?php

namespace App\Mail;

use App\Models\FundDisbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisbursementVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public FundDisbursement $disbursement;

    public function __construct(FundDisbursement $disbursement)
    {
        $this->disbursement = $disbursement;
    }

    public function build()
    {
        $subject = $this->disbursement->status === 'diterima'
            ? 'Pencairan Dana Anda Telah Disetujui'
            : 'Pencairan Dana Anda Ditolak';

        return $this
            ->subject($subject)
            ->view('emails.disbursement-verification');
    }
}
