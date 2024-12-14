@extends('user.index')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accountAndOrder/order.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row-order">
        <!-- Bên trái: Menu -->
        <div class="col-md-3 menu-left">
            @include('user.components.navbarOrderHistory')
        </div>


        <!-- Bên phải: Bộ lọc và danh sách đơn hàng -->
        <div class="col-md-9 order-right">
            <div class="button-header">
                <button>
                    Lịch sử đơn hàng <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="filter-section">
                <div class="btn-group w-100">
                    <button class="btn custom-filter-btn">Tất cả</button>
                    <button class="btn custom-filter-btn">Chờ xử lý</button>
                    <button class="btn custom-filter-btn">Đã xác nhận</button>
                    <button class="btn custom-filter-btn">Đang chuyển hàng</button>
                    <button class="btn custom-filter-btn">Đang giao hàng</button>
                    <button class="btn custom-filter-btn">Đã hủy</button>
                    <button class="btn custom-filter-btn">Thành công</button>
                </div>
            </div>

            @if ($orders->isEmpty())
            <p>Bạn chưa có đơn hàng nào.</p>
            @else
            @foreach ($orders as $order)
            <div class="card mb-4">
                @php
                $statusTranslations = [
                'pending' => 'Chờ xác nhận',
                'processing' => 'Đã xác nhận',
                'shipped' => 'Đang giao hàng',
                'delivered' => 'Đã giao hàng',
                'completed' => 'Hoàn Thành',
                'cancelled' => 'Đã hủy'
                ];

                $statusClasses = [
                'pending' => 'status-pending',
                'processing' => 'status-processing',
                'shipped' => 'status-shipped',
                'delivered' => 'status-delivered',
                'completed' => 'status-completed',
                'cancelled' => 'status-cancelled'
                ];
                @endphp
                <div class="card-header">
                    <span><strong>Đơn hàng:</strong> #{{ $order->order_id }}</span>
                    <span>Ngày đặt: {{ $order->order_date->format('d/m/Y H:i') }}</span>
                    <p class="{{ $statusClasses[strtolower($order->status)] }}">
                        {{ $statusTranslations[strtolower($order->status)] }}
                    </p>
                </div>

                <div class="card-body">
                    <ul class="order-items-list">
                        @foreach ($order->orderItems as $item)
                        <li class="order-item {{ $loop->index >= 2 ? 'hidden-item' : '' }}">
                            <div class="item-left">
                                <img src="{{ asset('storage/' . $item->product->main_image_url) }}"
                                    alt="{{ $item->product->name }}" />
                                <div class="item-info">
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <span class="item-quantity">x{{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="item-right">
                                <span class="item-price"><span class="custom-font-text-total">Tổng tiền:</span>
                                    {{ number_format($item->total, 0, ',', '.') }} đ</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @if (count($order->orderItems) > 2)
                    <button class="btn btn-link toggle-btn">Xem thêm</button>
                    @endif

                    <hr />
                    <p class="text-right"><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} đ
                    </p>
                    @php
                    $paymentStatusTranslations = [
                    'pending' => 'Đã thanh toán',
                    'paid' => 'Chưa thanh toán',
                    'failed' => 'Thanh toán thất bại',
                    ];
                    $paymentStatusClasses = [
                    'pending' => 'payment-pending',
                    'paid' => 'payment-paid',
                    'failed' => 'payment-failed',
                    ];
                    @endphp

                    <p class="text-right"> <strong>Trạng thái thanh toán:</strong>
                        <span class="{{ $paymentStatusClasses[strtolower($order->payment_status)] ?? '' }}">
                            {{ $paymentStatusTranslations[strtolower($order->payment_status)] ?? ucfirst($order->payment_status) }}
                        </span>
                    </p>

                    <div class="order-actions">
                        @if ($order->status === 'pending')
                        <form action="{{ route('user.order.cancelOrder', $order->order_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">Hủy đơn hàng</button>
                        </form>
                        @else
                        <button type="submit" class="btn btn-danger" disabled>Hủy đơn hàng</button>
                        @endif

                        <a href="{{ route('user.order.detail', $order->order_id) }}" class="btn btn-detail-custom">Chi
                            tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('.toggle-btn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const hiddenItems = document.querySelectorAll('.hidden-item');
            const isExpanded = toggleBtn.textContent === 'Ẩn bớt';

            hiddenItems.forEach(item => {
                item.style.display = isExpanded ? 'none' : 'flex';
            });

            toggleBtn.textContent = isExpanded ? 'Xem thêm' : 'Ẩn bớt';
        });
    }
});
</script>
