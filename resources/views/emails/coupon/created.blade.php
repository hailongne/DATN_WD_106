<!-- resources/views/emails/coupon/created.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Your New Coupon!</title>
</head>

<body>
    <h1>Chúc mừng bạn đã nhận được 1 phiếu giảm giá!</h1>
    <p>Code: {{ $coupon->code }}</p>
    @if($coupon->discount_amount)

        <p>Gía trị giảm giá:{{ number_format($coupon->discount_amount, 0, ',', '.') }} VNĐ</p>
    @else
        <p>Gía trị giảm giá:{{$coupon->discount_percentage	}} % </p>
    @endif
    <p>Start Date: {{ $coupon->start_date }}</p>
    <p>End Date: {{ $coupon->end_date }}</p>
</body>

</html>