@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{asset('css/style.css')}}">
@endpush
@section('content')


<body>

    <div class="container mt-4">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="container">

        <h1 class="text-center">Danh sách phiếu giảm giá</h1>
        <a href="{{route('admin.coupons.create')}}"><button class="btn add-button">Thêm mới</button></a>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th>STT</th>
                    <th>Tên mã phiếu giảm giá</th>
                    <th>Loại</th>
                    <th>Số lượng</th>
                    <th>Gía trị tối thiểu</th>
                    <th>Gía trị tối đa</th>
                    <th>Ngày bắt đầu </th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($coupons as $key => $coupon)
                <tr>
                    <td>{{$coupon->coupon_id}}</td>
                    <td>{{$coupon->code}}</td>
                    @if($coupon->discount_amount)
                    <td>{{ number_format($coupon->discount_amount, 0, ',', '.') }}</td>
                    @else
                    <td>{{$coupon->discount_percentage	}}%</td>
                    @endif
                    <td>{{$coupon->quantity}}</td>
                    <td>{{$coupon->min_order_value}}</td>
                    <td>{{$coupon->max_order_value}}</td>
                    <td>{{$coupon->start_date}}</td>
                    <td>{{$coupon->end_date}}</td>
                    <td>
                        <form action="{{ route('admin.coupons.toggle', $coupon->coupon_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn {{ $coupon->is_active ? 'btn-success' : 'btn-danger' }}">
                            <p>{{ $coupon->is_active ? 'Đang hoạt động' : 'Đã tắt hoạt động' }}</p>
                            </button>
                        </form>
                    </td>
                    <td class="action-icons">
                        <a href="{{route('admin.coupons.detail',$coupon->coupon_id)}}"> <i class="fas fa-eye text-info"
                                title="Chi tiết"></i></a>
                        <a href="{{route('admin.coupons.edit',$coupon->coupon_id)}}"><i class="fas fa-edit text-warning"
                                title="Sửa"></i></a>
                        <!-- Form xóa -->
                        <form action="{{ route('admin.coupons.delete', $coupon->coupon_id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu sắc này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @endforeach


            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
    </script>
    @endpush
</body>
@endsection
