<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CustomVerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $verificationUrl = $this->verificationUrl($this->user);

        return $this->subject('Companydeals: Verify Your Email Address')
            ->bcc('companydeals4u@gmail.com')
            ->view('emails.verifyEmail')
            ->with([
                'url' => $verificationUrl,
                'user' => $this->user
            ]);
    }

    protected function verificationUrl($user)
    {
        $expiration = Carbon::now()->addMinutes(\Config::get('auth.verification.expire', 60));

        return \URL::temporarySignedRoute(
            'user.email.verification.verify',
            $expiration,
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );
    }
}
