@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush
@section('content')

<body>
    <div class="container mt-5">
        <div class="button-header mb-3">
            <button>Danh sách phiếu giảm giá <i class="fa fa-star"></i></button>
            @if (Auth::user()->role !== 3)
            <a href="{{ route('admin.coupons.create') }}" class="btn add-button">Thêm mới</a>
            @endif
        </div>
        <div class="custom-filter-bar d-flex align-items-center">
            <form action="" method="get" class="d-flex align-items-center custom-filter-item">
                <div class="custom-input-group">
                    <input type="text" class="custom-form-control" name="nhap" placeholder="Tìm kiếm sản phẩm..."
                        aria-label="Search">
                    <button class="custom-btn custom-btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <form method="GET" class="custom-input-group">
                <div class="row">
                    <div class="col-md-6">
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-6">
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary m-2">Lọc</button>
            </form>
            <a href="{{ route('admin.coupons.index') }}" class="btn">
                <image src="{{ asset('imagePro/icon/icon-remove-filter.png') }}" style="width: 35px" />
            </a>
        </div>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Voucher</th>
                    <th>Tên</th>
                    <th>Giá trị giảm giá</th>
                    <th>Số lượng</th>
                    <th>Giá trị đơn hàng tối thiểu</th>
                    <th>Giảm giá tối đa</th>
                    <th>Ngày bắt đầu </th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($coupons as $key => $coupon)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @if($coupon->is_public==0)
                    <td>Public</td>
                    @else
                    <td>Private</td>
                    @endif
                    <td>{{ $coupon->code }}</td>
                    @if ($coupon->discount_amount)
                    <td>{{ number_format($coupon->discount_amount, 0, '.', ',') . ' VNĐ' }}</td>
                    @else
                    <td>{{ number_format($coupon->discount_percentage) }}%</td>
                    @endif
                    <td>{{ $coupon->quantity }}</td>
                    <td>{{ number_format($coupon->min_order_value) }} VNĐ</td>
                    @if($coupon->discount_amount)

                    <td>{{ number_format($coupon->max_order_value = $coupon->min_order_value) }} VNĐ</td>
                    @else
                    <td>{{ number_format($coupon->max_order_value) }} VNĐ</td>
                    @endif

                    <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>

                    <td>
                        <form action="{{ route('admin.coupons.toggle', $coupon->coupon_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="custom-btn-active-admin {{ $coupon->is_active ? 'btn-success' : 'btn-danger' }} status-btn-active">
                                <p>{{ $coupon->is_active ? 'Đang kích hoạt' : 'kích hoạt' }}</p>
                            </button>
                        </form>
                    </td>
                    <td class="action-icons">
                        <div class="icon-product d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon->coupon_id) }}">
                                <button class="action-btn edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Phân trang -->
        <nav>
            <ul class="pagination justify-content-center">
                {{ $coupons->links() }}
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script></script>
    @endpush
</body>
@endsection