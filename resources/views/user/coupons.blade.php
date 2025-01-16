@extends('user.index')

@section('content')
<link href="{{ asset('css/coupons.css') }}" rel="stylesheet">
<div class="button-header my-3">
    <button>
        Gentle Manor - Mã giảm giá của tôi <i class="fa fa-star"></i>
    </button>
</div>

@if($coupons->count() > 0)
@php
    // Lấy mảng coupon từ LengthAwarePaginator
    $couponsArray = $coupons->items();

    // Sắp xếp coupon theo trạng thái (Sắp hết hạn -> Còn lại -> Hết hạn)
    usort($couponsArray, function($a, $b) {
        // Lấy ngày hết hạn và số lượng coupon
        $endDateA = \Carbon\Carbon::parse($a->end_date);
        $endDateB = \Carbon\Carbon::parse($b->end_date);
        $now = \Carbon\Carbon::now();

        // Kiểm tra trạng thái hết hạn và sắp hết hạn cho từng coupon
        $expiredA = ($a->quantity <= 0 || $endDateA->isPast());
        $expiredB = ($b->quantity <= 0 || $endDateB->isPast());

        // Nếu một coupon hết hạn và cái còn lại không, cái hết hạn sẽ được xếp sau
        if ($expiredA !== $expiredB) {
            return $expiredA ? 1 : -1;
        }

        // Nếu cả hai đều sắp hết hạn, sắp xếp theo số ngày còn lại
        if (!$expiredA) {
            $diffA = $endDateA->diffInDays($now);
            $diffB = $endDateB->diffInDays($now);
            if ($diffA === $diffB) {
                return 0; // Nếu số ngày còn lại bằng nhau, không thay đổi vị trí
            }
            return $diffA < $diffB ? -1 : 1;
        }

        return 0; // Nếu đều đã hết hạn, không thay đổi thứ tự
    });
@endphp

<div class="coupon-container">
    @foreach($couponsArray as $coupon)
        @php
            $endDate = \Carbon\Carbon::parse($coupon->end_date);
            $now = \Carbon\Carbon::now();
            $quantity = $coupon->quantity;
            $expired = false;
            $aboutExpire = false;

            if ($quantity <= 0 || $endDate->isPast()) {
                $expired = true;
            } else {
                $diff = $endDate->diff($now);
                if ($diff->y === 0 && $diff->m === 0 && $diff->d <= 20) {
                    $aboutExpire = true;
                }
            }
        @endphp

        <div class="coupon-item @if($expired) expired @elseif($aboutExpire) about-expire @endif">
            <div class="coupon-logo">
                <img src="{{ asset('imagePro/image/logo/logo-remove2.png') }}" alt="Tiki Logo">
                <p class="coupon-source">Gentle Manor Coupons</p>
            </div>
            <div class="coupon-content">
                <h3 class="coupon-title">{{ $coupon->code }} </h3>
                <p class="coupon-description">
                    Giảm giá:
                    <a href="#" class="coupon-read-more">
                        @if($coupon->discount_percentage)
                        {{ $coupon->discount_percentage }}%
                        @elseif($coupon->discount_amount)
                        {{ number_format($coupon->discount_amount, 0, ',', '.') }} ₫
                        @endif
                    </a>
                </p>
                <p class="coupon-subtitle">Còn: {{ $coupon->quantity }} voucher</p>
                <p class="coupon-subtitle"> Áp dụng cho đơn hàng từ
                    <strong>{{ number_format($coupon->min_order_value, 0, ',', '.') }} ₫</strong> đến
                    <strong>{{ number_format($coupon->max_order_value, 0, ',', '.') }} ₫</strong>
                </p>
            </div>
            <div class="coupon-action mt-2">
                <div class="coupon-copy-code">
                    <button class="copy-btn" onclick="copyToClipboard('{{ $coupon->code }}')">
                        <i class="fas fa-copy"></i>
                    </button>
                    <span class="coupon-code">{{ $coupon->code }}</span>
                </div>
                <p class="coupon-expiry mt-4">Hạn SD: <strong>{{ date('d/m/Y', strtotime($coupon->end_date)) }}</strong></p>
                <p class="coupon-expiry mb-2">
                    @if ($expired)
                        <p class="buttom-about-exprie">Hết hạn</p>
                    @else
                        @php
                            $output = '';

                            if (isset($diff)) {
                                if ($diff->d > 0) {
                                    $output .= "Còn lại: {$diff->d} ngày ";
                                }
                                if ($diff->m > 0) {
                                    $output .= "{$diff->m} tháng ";
                                }
                                if ($diff->y > 0) {
                                    $output .= "{$diff->y} năm";
                                }
                            }

                            echo trim($output);
                        @endphp

                        @if ($aboutExpire)
                            <p class="buttom-about-exprie">Sắp hết hạn</p>
                        @endif
                    @endif
                </p>
            </div>
        </div>
    @endforeach
</div>
    <div class="pagination ml-5">
        {{ $coupons->links() }}
    </div>
    @else
    <p>Không có mã giảm giá nào khả dụng.</p>
    @endif

</div>
<script>
function copyToClipboard(couponCode) {
    navigator.clipboard.writeText(couponCode)
        .then(() => {
            Swal.fire({
                icon: 'success',
                text: `Sao chép mã coupon "${couponCode}".`,
                timer: 1000,
                showConfirmButton: false,
            });
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể sao chép mã coupon. Vui lòng thử lại!',
                timer: 1500,
                showConfirmButton: false,
            });
        });
}

</script>
@endsection
