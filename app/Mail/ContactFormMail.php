<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $messageText;

    // Perbaikan: Konstruktor menerima 4 argumen sesuai pemanggilan dari controller
    public function __construct($name, $email, $phone, $messageText)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->messageText = $messageText;
    }

    public function build()
    {
        // Gunakan email milikmu di 'from', dan email user di 'replyTo'
        return $this->from('redeemself0@gmail.com', 'Azka Garden')
            ->replyTo($this->email, $this->name)
            ->subject('Pesan Kontak dari Website Azka Garden')
            ->markdown('emails.contact_form');
    }
}