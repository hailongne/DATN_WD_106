<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirm extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    // Nhận dữ liệu từ controller
    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    // Tạo email
    public function build()
    {
        return $this->subject('Xác nhận đơn hàng từ cửa hàng')
            ->markdown('emails.mailOrder')  // Tạo email dưới dạng Markdown
            ->with('data', $this->emailData);    // Truyền dữ liệu vào view
    }
}
