<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    
    public $nama;
    public $username;
    public $encryptedUsername;
    public $ver_code;

    public function __construct($nama, $username, $encryptedUsername, $ver_code)
    {
        $this->nama = $nama;
        $this->username = $username;
        $this->encryptedUsername = $encryptedUsername;
        $this->ver_code = $ver_code;
    }

    public function build()
    {
        return $this->subject('Reset Password - SIM Global UNAIR')
                    ->view('emails.forgot_pass');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Forget Password Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
