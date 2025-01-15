<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .header {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #ddd;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .order-details {
            margin: 30px 0;
        }

        .order-details h2 {
            font-size: 20px;
            color: #444;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th, .order-details td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-details th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .order-summary {
            margin-top: 30px;
            text-align: right;
            font-size: 16px;
        }

        .order-summary h3 {
            font-size: 18px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Xác nhận đơn hàng</h1>
    </div>

    <div class="order-details">
        <h2>Thông tin người dùng:</h2>
        <p><strong>Tên người dùng:</strong> {{ $emailData['user']->name }}</p>
        <p><strong>Địa chỉ:</strong> {{ $emailData['user']->address }}</p>
        <p><strong>Số điện thoại:</strong> {{ $emailData['user']->phone  }}</p>

        <h2>Thông tin đơn hàng của bạn</h2>

        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Màu</th>
                    <th>Kích thước</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emailData['productDetails'] as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['color_id'] }}</td>
                        <td>{{ $product['size_id'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ number_format($product['price'], 0, ',', '.') }} VND</td>
                        <td>{{ number_format($product['subtotal'], 0, ',', '.') }} VND</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="order-summary">
        <h3>Tổng tiền đơn hàng: {{ number_format($emailData['total'], 0, ',', '.') }} VND</h3>
        <h3>Phí vận chuyển: {{ number_format($emailData['shippingFee'], 0, ',', '.') }} VND</h3>
        <h3><strong>Tổng cộng: {{ number_format($emailData['total'] , 0, ',', '.') }} VND</strong></h3>
    </div>

    <div class="footer">
        <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>
        <p>Địa chỉ cửa hàng: [Địa chỉ cửa hàng]</p>
        <p>Hotline: [Số điện thoại]</p>
    </div>

</div>

</body>
</html>
