<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Organization $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function build()
    {
        $subject = $this->organization->verification_status === 'disetujui'
            ? 'Organisasi Anda Telah Disetujui'
            : 'Verifikasi Organisasi Ditolak';

        return $this
            ->subject($subject)
            ->view('emails.organization-verification');
    }
}