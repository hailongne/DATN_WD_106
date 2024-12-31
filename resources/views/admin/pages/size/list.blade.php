@extends('admin.index')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container mt-5">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Danh Sách Kích Thước <i class="fa fa-star"></i></button>
    </div>
    <div class="container mt-5 ">
    <form action="" method="get" class="d-flex justify-content-center">
        <div class="input-group w-50">
            <!-- Nút tìm kiếm -->
            <button class="btn btn-primary" type="submit" name="btn">
                <i class="bi bi-search"></i> <!-- Icon tìm kiếm -->
            </button>
            <!-- Ô input tìm kiếm -->
            <input
                type="text"
                class="form-control"
                name="nhap"
                placeholder="Tìm kiếm sản phẩm..."
                aria-label="Search"
            >
        </div>
    </form>
</div>
    @if(Auth::user()->role !== 3)
    <a href="{{ route('admin.sizes.create') }}" class="btn add-button">Thêm mới</a>
    @endif
    <table class="product-table table table-bordered text-center align-middle mb-5">
        <thead class="thead-dark">
            <tr>
                <th>STT</th>
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
                        <a href="{{ route('admin.sizes.detail', $size->size_id) }}"  class=" text-info action-icons">
                            <button class="action-btn eye" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                        </a>
                        <a href="{{ route('admin.sizes.edit', $size->size_id) }}"  class="text-warning action-icons">
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
