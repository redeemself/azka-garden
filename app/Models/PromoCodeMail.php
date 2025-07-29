<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PromoCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $promoCode;

    /**
     * Create a new message instance.
     *
     * @param string $promoCode
     */
    public function __construct($promoCode)
    {
        $this->promoCode = $promoCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kode Promo Azka Garden')
            ->markdown('emails.promo')
            ->with([
                'promoCode' => $this->promoCode,
            ]);
    }
}
