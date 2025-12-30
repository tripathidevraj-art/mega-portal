<?php

namespace App\Mail;

use App\Models\ProductOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $offer;
    public $reason;

    public function __construct(ProductOffer $offer, $reason = null)
    {
        $this->offer = $offer;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Offer Has Been Approved')
                    ->view('emails.offer-approved')
                    ->with([
                        'offer' => $this->offer,
                        'reason' => $this->reason,
                    ]);
    }
}