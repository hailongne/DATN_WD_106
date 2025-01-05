@extends('admin.index')
@section('content')
<style>
.table td.color-code {
    color: #fff;
    font-weight: bold;
}
</style>

<body>

    <div class="container">

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
            <button>
                Danh sách màu sắc <i class="fa fa-star"></i>
            </button>
            @if(Auth::user()->role !== 3)
            <a href="{{ route('admin.colors.create') }}" class="btn add-button">Thêm mới</a>
            @endif
        </div>
        <div class="custom-filter-bar d-flex align-items-center">
            <form action="" method="get" class="d-flex justify-content-center">
                <div class="custom-input-group">
                    <input type="text" class="custom-form-control" name="nhap" placeholder="Tìm kiếm sản phẩm..."
                        aria-label="Search">
                    <button class="custom-btn custom-btn-primary" type="submit">
                        <i class="bi bi-search"></i> <!-- Icon tìm kiếm -->
                    </button>
                </div>
            </form>
            <a href="{{ route('admin.colors.index') }}" class="btn ml-3">
                <image src="{{ asset('imagePro/icon/icon-remove-filter.png') }}" style="width: 35px" />
            </a>
        </div>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 10%;">STT</th>
                    <th style="width: 30%;">Tên Màu Sắc</th>
                    <th style="width: 30%;">Mã Màu Sắc</th>
                    <th style="width: 30%;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colors as $color)
                <tr>
                    <td>{{ $color->color_id }}</td>
                    <td>{{ $color->name }}</td>
                    <td style="background-color: {{ $color->color_code }};">{{ $color->color_code }}</td>
                    <td>
                        <div class="icon-product d-flex justify-content-center gap-2">
                            <!-- Xem chi tiết -->
                            <a href="{{ route('admin.colors.detail', $color->color_id) }}">
                                <button class="action-btn eye" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>

                            <!-- Chỉnh sửa thông tin -->
                            <a href="{{ route('admin.colors.edit', $color->color_id) }}">
                                <button class="action-btn edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </a>

                            <!-- Xóa màu sắc -->
                            @if(Auth::user()->role !== 3)
                            <!-- Kiểm tra nếu không phải manager -->
                            <form action="{{ route('admin.colors.delete', $color->color_id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu sắc này?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Phân trang -->
        <nav>
            <ul class="pagination">
                {{ $colors->links() }}
            </ul>
        </nav>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    @push('scripts')
    @endpush

    @endsection