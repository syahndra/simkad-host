<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // pastikan ini PUBLIC

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Kode OTP Reset Password')
                    ->view('emails.reset_password_otp') // view di resources/views/emails/
                    ->with('data', $this->data);
    }
}