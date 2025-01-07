<!DOCTYPE html>
<html>
<head>
    <title>Cảnh báo tồn kho thấp</title>
</head>
<body>
    <h1>Cảnh báo tồn kho thấp cho sản phẩm</h1>
    <p>Mã sản phẩm: {{ $productSku }}</p>
    <p>  Tên sản phẩm: {{ $productName }}</p>
    <p>Màu sắc: {{ $color }}</p>
    <p>Kích cỡ: {{ $size }}</p>
    <p>Số lượng tồn kho hiện tại: {{ $stockQuantity }}</p>
    <p>Vui lòng kiểm tra và cập nhật lại số lượng tồn kho để tránh hết hàng.</p>
</body>
</html>
