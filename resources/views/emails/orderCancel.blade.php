<!DOCTYPE html>
<html>
<head>
    <title>Hủy đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #555;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details p {
            margin: 5px 0;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-items table, .order-items th, .order-items td {
            border: 1px solid #ddd;
        }
        .order-items th, .order-items td {
            padding: 10px;
            text-align: left;
        }
        .order-items th {
            background-color: #f4f4f4;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận hủy đơn hàng</h1>
        </div>
        <p>Chào {{ $order->user->name }},</p>
        <p>Đơn hàng mã <strong>{{ $order->order_id }}</strong> của bạn đã được hủy thành công.</p>
        <div class="order-details">
            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> Đã hủy</p>
        </div>
        <div class="order-items">
            <h3>Chi tiết đơn hàng:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Màu</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->size->name ?? 'N/A' }}</td>
                            <td>{{ $item->color->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                        <td><strong>{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ đội ngũ hỗ trợ khách hàng.</p>
        <div class="footer">
            <p>&copy; {{ now()->year }} Gentle Manor. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
