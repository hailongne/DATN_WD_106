@extends('user.index')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accountAndOrder/order.css') }}">
@endpush

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container">
    <div class="row-order">
        <!-- Bên phải: Chi tiết đơn hàng -->
        <div class="col-md-9 order-right">
            <div class="button-header">
                <button>
                    Chi tiết đơn hàng <i class="fa fa-star"></i>
                </button>
            </div>

            <!-- Thẻ đầu tiên: Thông tin nhận hàng -->
            <div class="row-custom-info">
                <div class="info-receive">
                    <p><i class="fa fa-map-marker-alt"></i> Thông tin nhận hàng</p>
                    <div class="details">
                        <p><i class="fa-solid fa-person"></i><strong> Người nhận:</strong> {{ $order->user->name }}</p>
                        <p><i class="fa-solid fa-phone"></i><strong> Số điện thoại:</strong> {{ $order->phone }}</p>
                        <p><i class="fa-solid fa-house-user"></i><strong>Nhận tại:</strong> {{ $order->shipping_address }}</p>

                    </div>
                </div>

                <!-- Thẻ thứ 2: Hình thức thanh toán -->
                <div class="payment-info">
                    <div class="details d-flex">
                        <p><i class="fa fa-credit-card mr-2"></i> <strong> Hình thức thanh toán: </strong>
                        </p>
                        <p>
                            @if($order->payment_method == 'COD')
                            Thanh toán khi nhận hàng
                            @else($order->payment_method == 'VNPAY')
                            VNPAY
                            @endif
                        </p>
                    </div>
                    @php
                    $statusLabels = [
                    'pending' => 'Đang chờ xử lý',
                    'shipped' => 'Đang vận chuyển',
                    'delivered' => 'Đã giao hàng',
                    'cancelled' => 'Đã hủy',
                    'completed' => 'Hoàn thành',
                    ];
                    $statusTranslations = [
                    'pending' => 'Chờ xử lý',
                    'paid' => 'Đã thanh toán',
                    'failed' => 'Thanh toán thất bại',
                    'refunded' => 'Hoàn tiền',
                    'cancelled' => 'Đã hủy',
                    ];

                    @endphp
                    <p><i class="fa-solid fa-truck"></i><strong>Trạng thái đơn hàng</strong>
                        {{ $statusTranslations[$order->payment_status] ?? 'Không xác định' }}
                    </p>
                    <p><i class="fa-solid fa-truck"></i><strong>Trạng thái đơn hàng</strong>
                        {{ $statusLabels[$order->status] ?? 'Không xác định' }}
                    </p>
                    <p><i class="fa-solid fa-calendar-days"></i><strong>Ngày đặt</strong>
                        {{ $order->created_at->format('d-m-Y H:i:s') }}
                    </p>
                    <p><i class="fa-solid fa-clock"></i><strong>Thời gian cập nhật</strong>
                        {{ $order->updated_at->format('d-m-Y H:i:s')}}
                    </p>
                </div>
            </div>

            <!-- Thẻ thứ 3: Thông tin sản phẩm -->
            <div class="product-info">
                <div class="details">
                    <h2><i class="fa fa-shopping-cart"></i>Sản phẩm trong đơn hàng</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Ảnh sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Màu sắc</th>
                                <th>Kích cỡ</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($item->product->image)
                                    <img src="/storage/{{ $item->product->main_image_url }}"
                                        alt="{{ $item->product->name }}"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <img src="/storage/{{ $item->product->main_image_url }}" alt="No Image"
                                        style="width: 50px; height: 50px;">
                                    @endif
                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->color ? $item->color->name : 'N/A' }}</td>
                                <td>{{ $item->size ? $item->size->name : 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="6">Tổng giá trị đơn hàng</td>
                                <td>{{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}
                                    VND</td>
                            </tr>
                            <tr>
                                <td colspan="6">Phí Ship</td>
                                <td>40,000 VND</td>
                            </tr>
                            <tr>
                                <td colspan="6">Thành tiền</td>
                                <td>{{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }) + 40000, 0, ',', '.') }}
                                    VND</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection