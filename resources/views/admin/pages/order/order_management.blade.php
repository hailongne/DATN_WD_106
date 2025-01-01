@extends('admin.index')
@section('content')

<body>
    <div class="container mt-4">
    <form method="GET" action="{{ route('admin.orders') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" placeholder="Ngày bắt đầu" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" placeholder="Ngày kết thúc" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('admin.orders') }}" class="btn btn-secondary">Đặt lại</a>
            </div>
        </div>
    </form>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Trạng thái đơn hàng</th>
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
                            data-initial-status="{{ $order->status }}"
                            data-received-delivery="{{ $order->received}}">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total, 2) }} VND</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.orderDetail', $order->order_id) }}" class="fas fa-eye text-success"></a>
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
            alert('Không thể chuyển trạng thái từ "' + initialStatus.charAt(0).toUpperCase() + initialStatus.slice(1) + '" đến "' + currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1) + '"!');
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
