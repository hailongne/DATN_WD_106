@extends('user.index')

@section('content')
<div class="container">
    <h2>Danh sách mã giảm giá</h2>
    
    @if($coupons->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã giảm giá</th>
                    <th>Giảm giá</th>
                    <th>Lượt sử dụng</th>
                    <th>Giá trị tối thiểu cho đơn hàng để sử dụng</th>
                    <th>Giá trị tối đa cho đơn hàng để sử dụng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>
                            @if($coupon->discount_percentage)
                                {{ $coupon->discount_percentage }}%
                            @elseif($coupon->discount_amount)
                            {{ number_format($coupon->discount_amount, 0, ',', '.') }} VND
                            @endif
                        </td>
                        <td>{{ $coupon->quantity }}</td>
                        <td>{{ number_format($coupon->min_order_value, 0, ',', '.') }} VND</td>
                        <td>{{ number_format($coupon->max_order_value, 0, ',', '.') }} VND</td>
                        <td>{{ $coupon->start_date }}</td>
                        <td>{{ $coupon->end_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $coupons->links() }}
        </div>
    @else
        <p>Không có mã giảm giá nào khả dụng.</p>
    @endif
</div>
@endsection
