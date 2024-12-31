@extends('admin.index')
@section('content')
@push('styles')
<style>
.cusstom-no-image {
    width: 50px !important;
    height: 50px !important;
}
</style>
@endpush
<div class="container mt-4">
    <!-- Tiêu đề -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="button-header">
        <button>
            Danh sách danh mục <i class="fa fa-star"></i>
        </button>
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
    <a href="{{ route('admin.categories.create') }}" class="btn add-button"> Thêm mới </a>
    @endif

    <!-- Bảng danh sách danh mục -->
    <table class="product-table table table-bordered text-center align-middle mb-5">
        <thead class="thead-dark">
            <tr>
                <th style="width: 10%;">STT</th>
                <th style="width: 20%;">Tên Danh mục</th>
                <th style="width: 20%;">Hình ảnh</th>
                <th style="width: 20%;">Trạng thái</th>
                <th style="width: 20%;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <img src="{{Storage::url($category->image)}} " class="cusstom-no-image"
                        onerror="this.onerror=null; this.src='{{ asset('imagePro/icon/icon-no-image.png') }}';">
                </td>
                <td>
                    <form action="{{ route('admin.categories.toggle', $category->category_id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="custom-btn-active-admin {{ $category->is_active ? 'btn-success' : 'btn-danger' }}">
                            <p>{{ $category->is_active ? 'Đang hoạt động' : 'Đã tắt hoạt động' }}</p>
                        </button>
                    </form>
                </td>
                <td>
                    <div class="icon-product d-flex justify-content-center gap-2">
                        <!-- Xem -->
                        <a href="{{ route('admin.categories.detail', $category->category_id) }}">
                            <button class="action-btn eye"><i class="fas fa-eye"></i></button>
                        </a>
                        <!-- Sửa -->
                        <a href="{{ route('admin.categories.edit', $category->category_id) }}">
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                        </a>
                        <!-- Xóa -->
                        @if(Auth::user()->role !== 3)
                        <!-- Kiểm tra nếu không phải manager -->
                        <form action="{{ route('admin.categories.delete', $category->category_id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
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
        <ul class="pagination">
            {{ $categories->links() }}
        </ul>
    </nav>
</div>

<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


@endsection
