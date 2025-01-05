@extends('admin.index')
@section('content')
<style>
.text-warning {
    color: #ffc107;
}

.text-success {
    color: #28a745;
}

.text-danger {
    color: #dc3545;
}

.text-secondary {
    color: #6c757d;
}

select.form-control {
    color: #495057;
}

select.form-control option[value="pending"] {
    color: #ffc107;
}

select.form-control option[value="processing"] {
    color: #17a2b8;
}

select.form-control option[value="shipped"] {
    color: #007bff;
}

select.form-control option[value="delivered"] {
    color: #28a745;
}

select.form-control option[value="completed"] {
    color: #6c757d;
}

select.form-control option[value="cancelled"] {
    color: #dc3545;
}
</style>

<body>
    <div class="button-header mb-3">
        <button>Quản lý đơn hàng <i class="fa fa-star"></i></button>
    </div>

    <div class="container mt-4">
        <form method="GET" action="{{ route('admin.orders') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" placeholder="Ngày bắt đầu"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" placeholder="Ngày kết thúc"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.orders') }}" class="btn btn-secondary">Đặt lại</a>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </div>
        </form>

        <table class="product-table table table-bordered text-center align-middle">
            <thead class="thead-dark">
                <tr>
                    <th>Mã</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày mua hàng</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_id }}</td>
                    <td>
                        <select class="form-control status-select" data-order-id="{{ $order->order_id }}"
                            data-initial-status="{{ $order->status }}" data-received-delivery="{{ $order->received }}">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang chờ xử lý
                            </option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đã xác
                                nhận</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang vận chuyển
                            </option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng
                            </option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </td>
                    <td>
                        @switch($order->payment_status)
                        @case('pending')
                        <span class="text-warning">Chờ xử lý</span>
                        @break
                        @case('paid')
                        <span class="text-success">Đã thanh toán</span>
                        @break
                        @case('failed')
                        <span class="text-danger">Thất bại</span>
                        @break
                        @default
                        <span class="text-secondary">N/A</span>
                        @endswitch
                    </td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total, 2) }} VND</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.orderDetail', $order->order_id) }}"
                            class="fas fa-eye text-success"></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Script xử lý AJAX -->
    <script>
    $(document).on('change', '.status-select', function() {
        var currentStatus = $(this).val(); // Lấy trạng thái hiện tại
        var orderId = $(this).data('order-id'); // Lấy ID đơn hàng
        var initialStatus = $(this).data('initial-status'); // Lấy trạng thái ban đầu
        var receivedDelivery = $(this).data('received-delivery'); // Lấy thông tin received_delivery

        // Mảng chứa các trạng thái hợp lệ cho từng trạng thái hiện tại
        var validStatuses = {
            "pending": ["processing", "cancelled"],
            "processing": ["shipped"],
            "shipped": ["delivered"],
            "delivered": [],
            "completed": [],
            "cancelled": [] // Không thể thay đổi trạng thái nếu đã bị hủy
        };

        // Kiểm tra trạng thái mới có hợp lệ không
        if (!validStatuses[initialStatus].includes(currentStatus)) {
            alert('Không thể chuyển trạng thái từ "' + initialStatus.charAt(0).toUpperCase() + initialStatus
                .slice(1) + '" đến "' + currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1) +
                '"!');
            $(this).val(initialStatus); // Đặt lại trạng thái ban đầu
            return false;
        }

        // Kiểm tra nếu trạng thái muốn chuyển sang "completed" nhưng người dùng chưa xác nhận nhận hàng
        if (currentStatus === 'completed' && !receivedDelivery) {
            alert('Không thể chuyển trạng thái thành "Completed" vì khách hàng chưa xác nhận đã nhận hàng.');
            $(this).val(initialStatus); // Đặt lại trạng thái ban đầu
            return false;
        }

        // Nếu hợp lệ, gửi yêu cầu AJAX hoặc cập nhật trạng thái
        $.ajax({
            url: "{{ route('admin.updateOrderStatus') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                status: currentStatus
            },
            success: function(response) {
                if (response.success) {
                    alert('Cập nhật trạng thái thành công!');
                    location.reload(); // Reload trang sau khi cập nhật thành công
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Không thể kết nối đến server. Vui lòng thử lại.');
                console.error(xhr.responseText);
            }
        });
    });
    </script>
</body>
@endsection