@extends('admin.index')

@section('content')

<style>
.action-btn i {
    font-size: 18px;
}

.table th,
.table td {
    font-size: 18px;
    line-height: 1.2;
}
</style>
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
    <!-- Tiêu đề -->
    <div class="button-header mb-3">
        <button>Danh Sách Kích Thước <i class="fa fa-star"></i></button>
        @if(Auth::user()->role !== 3)
        <a href="{{ route('admin.sizes.create') }}" class="btn add-button">Thêm mới</a>
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
        <a href="{{ route('admin.sizes.index') }}" class="btn ml-3">
            <image data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Bỏ lọc"
                src="{{ asset('imagePro/icon/icon-remove-filter.png') }}" style="width: 35px" />
        </a>
    </div>
    <table class="product-table table table-bordered text-center align-middle mb-5">
        <thead class="thead-dark">
            <tr>
                <th></th>
                <th>Tên kích thước</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sizes as $index => $size)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $size->name }}</td>
                <td>
                    <div class="icon-product d-flex justify-content-center gap-2">
                        <!-- <a href="{{ route('admin.sizes.detail', $size->size_id) }}" class=" text-info action-icons">
                            <button class="action-btn eye" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                        </a> -->
                        <a href="{{ route('admin.sizes.edit', $size->size_id) }}" class="text-warning action-icons">
                            <button class="action-btn edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                        </a>
                        @if(Auth::user()->role !== 3)
                        <form action="{{ route('admin.sizes.delete', $size->size_id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa kích thước này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
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
        <ul class="pagination justify-content-center">
            {{ $sizes->links() }}
        </ul>
    </nav>
</div>



<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
