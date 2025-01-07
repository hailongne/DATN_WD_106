<?php

namespace App\Mail;

use App\Models\AttributeProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $attributeProduct;
    public function __construct(AttributeProduct $attributeProduct)
    {
        //
        $this->attributeProduct = $attributeProduct;
    }
    public function build()
    {
        return $this->subject('Cảnh báo tồn kho thấp')
                    ->view('emails.low_stock_alert')
                    ->with([
                        'productSku' => $this->attributeProduct->product->sku,
                        'productName' => $this->attributeProduct->product->name,
                        'color' => $this->attributeProduct->color->name,
                        'size' => $this->attributeProduct->size->name,
                        'stockQuantity' => $this->attributeProduct->in_stock,
                    ]);
    }


}
