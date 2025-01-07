<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông tin đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            font-size: 24px;
            color: #333333;
        }
        .email-body {
            margin-top: 20px;
        }
        .email-body h2 {
            font-size: 20px;
            color: #444444;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-details th, .order-details td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        .order-details th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .order-summary {
            margin-top: 20px;
            font-size: 16px;
        }
        .order-summary p {
            margin: 0;
            padding: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Cập nhật trạng thái đơn hàng</h1>
        </div>
        <div class="email-body">
            <h2>Xin chào {{ $order->user->name }},</h2>
            <p>Trạng thái đơn hàng của bạn đã được cập nhật.</p>
            <p><strong>Mã đơn hàng:</strong> {{ $order->order_id }}</p>
            <p><strong>Trạng thái mới:</strong> {{ $newStatus }}</p>
            
            <h3>Chi tiết đơn hàng:</h3>
            <table class="order-details">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Size</th>
                        <th>Màu</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->size->name ?? 'N/A' }}</td>
                            <td>{{ $item->color->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
        </div>
    </div>
</body>
</html>
