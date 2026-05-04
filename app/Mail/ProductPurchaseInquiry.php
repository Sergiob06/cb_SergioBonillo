<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductPurchaseInquiry extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Product $product,
        public array $customerData
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud de compra: ' . $this->product->name,
            replyTo: [
                new Address($this->customerData['email'], $this->customerData['name']),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-purchase-inquiry',
        );
    }
}
