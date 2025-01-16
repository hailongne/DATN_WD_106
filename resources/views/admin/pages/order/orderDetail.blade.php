@extends('admin.index')
@section('content')

<body>
    <div class="button-header mb-3">
        <button>Chi tiết đơn hàng<strong> #{{ $order->order_id }} </strong> <i class="fa fa-star"></i></button>
    </div>
    @php
    $statusLabels = [
    'pending' => 'Đang chờ xử lý',
    'processing' => 'Đã xác nhận',
    'shipped' => 'Đang vận chuyển',
    'delivered' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy',
    'completed' => 'Hoàn thành',
    ];
    $statusTranslations = [
    'pending' => 'Chờ thanh toán',
    'paid' => 'Đã thanh toán',
    'failed' => 'Thanh toán thất bại',
    'refunded' => 'Hoàn tiền',
    'cancelled' => 'Đã hủy',
    ];
    @endphp
    <?php
    $defaultPendingStatus = (object) [
        'new_status' => 'pending',
        'created_at' => $order->created_at,
        'updatedBy' => null,
    ];
    if (!$order->statusHistories->contains(fn($history) => strtolower($history->new_status) === 'pending')) {
        $order->statusHistories->prepend($defaultPendingStatus);
    }
    ?>
    <div class="card align-items-center justify-content-between mb-4">
        <div class=" title-card d-flex row">
            <div class="text-back d-flex col">
                <a href="{{ route('admin.orders') }}">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
            <div class="custom-status col">
                <span class="text-info ml-3">{{ $statusLabels[$order->status] ?? 'Không xác định' }}</span>
                <span class=" text-nowrap">MÃ ĐƠN HÀNG: #{{ $order->order_id }} <span class="ml-3">|</span></span>
            </div>
        </div>
        <hr />
        <div class="card-order-history">
            <div class="timeline mb-3">
            @foreach ($order->statusHistories as $history)
            <div class="step completed">
                <div class="icon">
                    @switch(strtolower($history->new_status))
                    @case('pending')
                    <i class="fas fa-clock"></i>
                    @break
                    @case('processing')
                    <i class="fas fa-spinner"></i>
                    @break
                    @case('shipped')
                    <i class="fas fa-truck"></i>
                    @break
                    @case('delivered')
                    <i class="fas fa-box-open"></i>
                    @break
                    @case('cancelled')
                    <i class="fas fa-times-circle"></i>
                    @break
                    @case('completed')
                    <i class="fas fa-check-circle"></i>
                    @break
                    @default
                    <i class="fas fa-question-circle"></i>
                    @endswitch
                </div>
                <div class="text">
                    <p>{{ $statusLabels[strtolower($history->new_status)] ?? $history->new_status }}</p>
                    <span>{{ $history->created_at->format('H:i d-m-Y') }}</span>
                    @if (strtolower($history->new_status) !== 'pending') <!-- Kiểm tra trạng thái -->
                    <br />
                    <span>Cập nhật bởi: <strong>{{ $history->updatedBy->name ?? 'Không xác định' }}</strong></span>
                    @endif
                </div>
            </div>
            @endforeach

            </div>
            <hr />
            <div class="order-tracking-container-special-long-classname">
                <div class="recipient-address-section-super-unique-classname">
                    <h2>Thông tin khách hàng</h2>
                    <p><strong>{{ $order->recipient_name }}</strong></p>
                    <p><span class="custom-title-profile">Email:</span>{{ $order->user->email }}</p>
                    <p><span class="custom-title-profile">SĐT:</span>{{ $order->phone }}</p>
                    <p><span class="custom-title-profile">Địa chỉ:</span>{{ $order->shipping_address  }}</p>
                    <br />
                    <h2>Thông tin đơn hàng</h2>
                    <p><span class="custom-title-profile">Mã đơn hàng: </span><strong> #{{ $order->order_id }}</strong>
                    </p>
                    <p><span class="custom-title-profile">Trạng thái đơn hàng:
                        </span>{{ $statusLabels[$order->status] ?? 'Không xác định' }}</p>
                    <p><span class="custom-title-profile">Trạng thái thanh toán:</span>
                        {{ $statusTranslations[$order->payment_status] ?? 'Không xác định' }}</p>
                    <p><span class="custom-title-profile">Ngày mua hàng:</span>
                    {{ $order->order_date->format('d-m-Y') }}</p>
                    <p><span class="custom-title-profile">Thời gian mua hàng:</span>
                        {{ $order->order_date->format('H:i:s') }}</p>
                    <p><span class="custom-title-profile">Ngày cập nhật đơn hàng:
                        </span>{{ $order->updated_at->format('d-m-Y') }} </p>
                    <p><span class="custom-title-profile">Thời gian cập nhật:</span>
                        {{ $order->updated_at->format('H:i:s') }} </p>
                </div>
                <div class="order-status-timeline-section-extremely-long-classname">
                    @if ($order->statusHistories->isNotEmpty())
                    <ul class="order-status-ul-timeline-ul-super-detailed-long-classname ml-5">
                        @foreach ($order->statusHistories as $history)
                        <li class="timeline-step-special-status-{{ strtolower($history->new_status) }}">
                            <div class="timeline-status-time-special-classname">
                                {{ $history->created_at->format('H:i d-m-Y') }}
                            </div>
                            <div class="timeline-step-description-details-classname">
                                <p><strong>{{ $statusLabels[strtolower($history->new_status)] ?? $history->new_status }}</strong></p>
                                @if (strtolower($history->new_status) !== 'pending') <!-- Kiểm tra trạng thái -->
                                <p>Cập nhật bởi: <strong>{{ $history->updatedBy->name ?? 'Không xác định' }}</strong></p>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-table-special-long-classname">
            <table class="product-table table table-bordered text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Màu sắc</th>
                        <th>Kích cỡ</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $index => $orderItem)
                    <tr>
                        <td class="text-center">
                            <img src="/storage/{{ $orderItem->product->main_image_url }}"
                                alt="{{ $orderItem->product->name }}" width="50" height="50">
                        </td>
                        <td>{{ $orderItem->product->name }}</td>
                        <td>{{ number_format($orderItem->attributeProduct->price ?? 0) }} đ</td>
                        <td>{{ $orderItem->color ? $orderItem->color->name : 'N/A' }}</td> <!-- Màu sắc -->
                        <td>{{ $orderItem->size ? $orderItem->size->name : 'N/A' }}</td> <!-- Kích cỡ -->
                        <td class="text-center">{{ $orderItem->quantity }}</td>
                        <td class="text-right text-danger"><strong>
                                {{ number_format($orderItem->attributeProduct->price * $orderItem->quantity, 0, 2) }}</strong>đ
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end">Tổng giá trị đơn hàng</td>
                        <td class="text-end">
                            {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}đ
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end">Phí Ship</td>
                        <td class="text-end">40,000đ</td>
                    </tr>

                    <tr>
                        <td colspan="6" class="text-end">Giảm giá</td>
                        <td class="text-end">
                        {{ number_format(
                                        ($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }) + 40000) - $order->total
                                        , 0, ',', '.') }} đ
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end">Thành tiền</td>
                        <td class="text-end text-danger"><strong>
                        {{ number_format($order->total, 0, ',', '.') }} đ</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Lý do hủy đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="cancelForm">
                        <div class="form-group">
                            <label for="reason">Nhập lý do hủy:</label>
                            <textarea class="form-control" id="reason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        this.classList.add('btn-cancel');
        document.getElementById('cancelOrderBtn').classList.add('btn-cancel');
        document.getElementById('cancelOrderBtn').innerText = 'Đã hủy';
        document.getElementById('cancelOrderBtn').disabled = true;
    });

    document.getElementById('cancelOrderBtn').addEventListener('click', function() {
        $('#cancelModal').modal('show');
    });

    document.getElementById('cancelForm').addEventListener('submit', function(event) {
        event.preventDefault();
        // Xử lý logic hủy đơn hàng ở đây
        $('#cancelModal').modal('hide');
        alert('Đơn hàng đã được hủy với lý do: ' + document.getElementById('reason').value);
    });
    </script>
    @endpush
</body>







@endsection
