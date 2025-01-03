<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;
    public $previousStatus;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $previousStatus, $newStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Cập nhật trạng thái đơn hàng')
                    ->view('emails.orderStatusUpdated');
    }
}
