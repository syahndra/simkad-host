<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userEmail;
    public $newPassword;

    public function __construct($userEmail, $newPassword)
    {
        $this->userEmail = $userEmail;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Password Anda Berhasil Direset')
            ->view('emails.password_reset_success');
    }
}
