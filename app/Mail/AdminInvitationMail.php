<?php

namespace App\Mail;

use App\Models\AdminInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public AdminInvitation $invitation;

    public function __construct(AdminInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this
            ->subject('Undangan Pendaftaran Akun ' . $this->invitation->roleLabel() . ' Rangkul')
            ->view('emails.admin-invitation', [
                'url' => $this->invitation->registrationUrl(),
            ]);
    }
}
