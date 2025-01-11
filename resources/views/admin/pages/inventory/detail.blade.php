@extends('admin.index')
@section('content')
@push('styles')
<style>
    /* CSS */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px; /* Khoảng cách giữa các form */
    margin-top: 30px; /* Khoảng cách trên */
}

</style>
<link rel="stylesheet" href="{{ asset('css/huongDetail.css') }}">
@endpush

    <body>
    <div class="container mt-5">
    <div class="button-header">
                <button>
                    Chi tiết sản phẩm <i class="fa fa-star"></i>
                </button>
            </div>
<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên Sản Phẩm</th>
            <th>Kích cỡ</th>
            <th>Màu</th>
            <th>Gíai</th>
            <th>Số lượng</th>
            <th>Hoạt động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attPros as $attPro )
        <tr>
            <td>{{$attPro->product->sku}}</td>
            <td>{{$attPro->product->name}}</td>
            <td>{{$attPro->size->name}}</td>
            <td>{{$attPro->color->name}}</td>
            <td>{{ number_format($attPro->price, 0, ',', '.') }} đ</td>
            <td>{{$attPro->in_stock}}</td>
            <td>
                            <div class="icon-product d-flex justify-content-center gap-2">
                          
                                <!-- Sửa -->
                                <a href="{{ route('admin.inventories.edit', $attPro->attribute_product_id ) }}">
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                </a>
                            </div>
                        </td>
        </tr>

        @endforeach
        <!-- Có thể thêm nhiều hàng hơn ở đây -->
    </tbody>
</table>
</div>
</body>
    <script>
    </script>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @endpush
@endsection