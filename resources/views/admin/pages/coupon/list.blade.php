@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{asset('css/style.css')}}">
@endpush
@section('content')


<body>



    <div class="container mt-5">
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="button-header mb-3">
            <button>Danh sách phiếu giảm giá <i class="fa fa-star"></i></button>
            @if(Auth::user()->role !== 3)
            <a href="{{route('admin.coupons.create')}}" class="btn add-button">Thêm mới</a>
            @endif
        </div>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Tên</th>
                    <th>Loại</th>
                    <th>Số lượng</th>
                    <th>Giá trị tối thiểu</th>
                    <th>Giá trị tối đa</th>
                    <th>Ngày bắt đầu </th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th></th>
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
                            <button type="submit"
                                class="custom-btn-active-admin {{ $coupon->is_active ? 'btn-success' : 'btn-danger' }} status-btn-active">
                                <p>{{ $coupon->is_active ? 'Kích hoạt' : 'Hủy' }}</p>
                            </button>
                        </form>
                    </td>
                    <td class="action-icons">
                        <div class="icon-product d-flex justify-content-center gap-2">
                            <!-- <a href="{{route('admin.coupons.detail',$coupon->coupon_id)}}">
                                <button class="action-btn eye" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a> -->
                            <a href="{{route('admin.coupons.edit',$coupon->coupon_id)}}">
                                <button class="action-btn edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </a>
                            <!-- Form xóa -->
                            <form action="{{ route('admin.coupons.delete', $coupon->coupon_id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu sắc này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete">
                                    <i class="fas fa-trash-alt" title="Xóa"></i>
                                </button>
                            </form>
                        </div>
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