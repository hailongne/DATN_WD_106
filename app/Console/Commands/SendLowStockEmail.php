<?php

namespace App\Console\Commands;

use App\Models\AttributeProduct;
use App\Mail\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLowStockEmail extends Command
{
    protected $signature = 'stock:send-low-stock-email';
    protected $description = 'Gửi email cảnh báo khi số lượng tồn kho thấp';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lấy tất cả sản phẩm thuộc tính có tồn kho thấp hơn ngưỡng
        $attributeProducts = AttributeProduct::where('in_stock', '<=', 'warning_threshold')->get();

        // Duyệt qua từng sản phẩm thuộc tính và gửi email cảnh báo
        foreach ($attributeProducts as $attributeProduct) {
            $this->sendLowStockEmail($attributeProduct);
        }

        $this->info('Email cảnh báo tồn kho đã được gửi!');
    }

    // Hàm gửi email cảnh báo
    protected function sendLowStockEmail($attributeProduct)
    {
        // Địa chỉ email của quản trị viên
        $adminEmail = 'longxahoi7@gmail.com';  // Thay bằng email của admin

        // Gửi email
        Mail::to($adminEmail)->send(new LowStockAlert($attributeProduct));
    }
}

