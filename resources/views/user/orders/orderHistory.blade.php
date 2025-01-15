@extends('user.index')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accountAndOrder/order.css') }}">
@endpush

@section('content')

<div class="container">
    <!-- Bên phải: Bộ lọc và danh sách đơn hàng -->
    <div class="col-md-12 order-all-history">
        <div class="button-header">
            <button>
                Lịch sử đơn hàng <i class="fa fa-star"></i>
            </button>
        </div>

        <div class="filter-section">
            <div class="btn-group w-100">
                <button class="btn custom-filter-btn" data-filter="all">Tất cả</button>
                <button class="btn custom-filter-btn" data-filter="pending">Chờ xử lý</button>
                <button class="btn custom-filter-btn" data-filter="processing">Đã xác nhận</button>
                <button class="btn custom-filter-btn" data-filter="shipped">Đang chuyển hàng</button>
                <button class="btn custom-filter-btn" data-filter="delivered">Đang giao hàng</button>
                <button class="btn custom-filter-btn" data-filter="cancelled">Đã hủy</button>
                <button class="btn custom-filter-btn" data-filter="completed">Thành công</button>
            </div>
        </div>

        <div id="order-list">
            @if ($orders->isEmpty())
            <p>Bạn chưa có đơn hàng nào.</p>
            @else
            @foreach ($orders as $order)
            <div class="card mb-4 order-card" data-status="{{ strtolower($order->status) }}">
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

                <div class="card-list-product-order-history">
                    <div class="card-header">
                        <span><strong>Đơn hàng:</strong> #{{ $order->order_id }}</span>
                        <span>Ngày đặt: {{ $order->order_date->format('d/m/Y H:i') }}</span>
                        <p class="{{ $statusClasses[strtolower($order->status)] }}">
                            {{ $statusTranslations[strtolower($order->status)] }}
                        </p>
                    </div>

                    <div class="card-body-order-history">
                        <ul class="order-items-list">
                            @foreach ($order->orderItems as $item)
                            <li class="order-item {{ $loop->index >= 2 ? 'hidden-item' : '' }}">
                                <div class="item-left mr-5">
                                    @if ($item->product)
                                    <img src="{{ asset('storage/' . $item->product->main_image_url) }}"
                                        alt="{{ $item->product->name }}" />
                                    <div class="item-info">
                                        <span class="title-item-info">{{ $item->product->name }}</span>
                                        <br>
                                        <div class="attribute-info-size">
                                            <strong>Kích thước:
                                                {{ $item->color ? $item->size->name : 'Không có thông tin' }}</strong>
                                        </div>
                                        <div class="attribute-info-color">
                                            <strong>Màu sắc:
                                                {{ $item->color ? $item->color->name : 'Không có thông tin' }}</strong>
                                        </div>
                                        <span class="item-quantity">x{{ $item->quantity }}</span>
                                    </div>
                                    @else
                                    <div class="item-info">
                                        <strong>Sản phẩm này đã bị xóa</strong><br>
                                        <span class="item-quantity">x{{ $item->quantity }}</span>
                                        <div class="attribute-info">
                                            <div>
                                                <strong>Kích thước:
                                                    {{ $item->color ? $item->size->name : 'Không có thông tin' }}</strong>
                                            </div>
                                            <div>
                                                <strong>Màu sắc:
                                                    {{ $item->color ? $item->color->name : 'Không có thông tin' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="item-left">
                                <span class="price-default">{{ $item->product ? number_format($item->price, 0, ',', '.')  : 'Không có thông tin' }}₫</span>
                                </div>
                                <div class="item-right">
                                    <span class="item-price">
                                        {{ $item->product ? number_format($item->total, 0, ',', '.') . '₫' : 'Không có thông tin' }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @if (count($order->orderItems) > 2)
                        <button class="btn btn-link toggle-btn">Xem thêm</button>
                        @endif

                        <hr />
                        <p class="text-right">
                            <strong>Thành tiền:</strong>
                            <span class="thanh-tien">{{ number_format($order->total, 0, ',', '.') }}₫</span>

                        </p>

                        @php
                        $paymentStatusTranslations = [
                        'pending' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                        'failed' => 'Thanh toán thất bại',
                        ];
                        $paymentStatusClasses = [
                        'pending' => 'payment-pending',
                        'paid' => 'payment-paid',
                        'failed' => 'payment-failed',
                        ];
                        @endphp

                        <p class="text-right trang-thai-thanh-toan"> <strong>Trạng thái thanh toán:</strong>
                            <span class="{{ $paymentStatusClasses[strtolower($order->payment_status)] ?? '' }}">
                                {{ $paymentStatusTranslations[strtolower($order->payment_status)] ?? ucfirst($order->payment_status) }}
                            </span>
                        </p>

                        <div class="order-actions">
                            @if ($order->status == 'delivered' && !$order->received_confirmation)
                            <form action="{{ route('user.order.confirmDelivery', $order->order_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info">Đã Nhận Hàng</button>
                            </form>
                            @endif
                            @if ($order->status === 'pending')
                            <form action="{{ route('user.order.cancelOrder', $order->order_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">Hủy đơn
                                    hàng</button>
                            </form>
                            @else
                            <button type="submit" class="btn btn-danger" disabled>Hủy đơn hàng</button>
                            @endif

                            <a href="{{ route('user.order.detail', $order->order_id) }}"
                                class="btn btn-primary"><span>Chi tiết</span></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle xem thêm ẩn bớt
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', function() {
            const hiddenItems = this.closest('.card').querySelectorAll('.hidden-item');
            const isExpanded = this.textContent.trim() === 'Ẩn bớt';

            hiddenItems.forEach(item => {
                item.style.display = isExpanded ? 'none' : 'flex';
            });

            this.textContent = isExpanded ? 'Xem thêm' : 'Ẩn bớt';
        });
    });

    // Bộ lọc theo trạng thái đơn hàng
    document.querySelectorAll('.custom-filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            document.querySelectorAll('.order-card').forEach(card => {
                const status = card.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            document.querySelectorAll('.custom-filter-btn').forEach(btn => btn.classList.remove(
                'active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush
