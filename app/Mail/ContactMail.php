<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $contactData;

    /**
     * Create a new message instance.
     *
     * @param array $contactData
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Pastikan "From" adalah emailmu (tidak bisa dari email user jika pakai Gmail SMTP)
        // "Reply-To" diisi email user agar admin bisa membalas langsung ke user
        return $this->from('redeemself0@gmail.com', 'Azka Garden')
            ->replyTo($this->contactData['email'], $this->contactData['name'])
            ->subject('Pesan Kontak dari Website Azka Garden')
            ->markdown('emails.contact', ['contact' => $this->contactData]);
    }
}