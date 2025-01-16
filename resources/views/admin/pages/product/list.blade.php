@extends('admin.index')
@section('content')
@push('styles')
<style>
/* CSS */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px;
    /* Khoảng cách giữa các form */
    margin-top: 30px;
    /* Khoảng cách trên */
}
</style>
@endpush

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
                Danh sách sản phẩm <i class="fa fa-star"></i>
            </button>

            @if(Auth::user()->role !== 3)
            <!-- Kiểm tra nếu không phải manager -->
            <a href="{{route('admin.products.create')}}" class="btn add-button">Thêm mới</a>
            @endif
        </div>

        <!-- Bộ Áp dụng -->
        <div class="custom-filter-bar d-flex align-items-center">
            <!-- Form Tìm kiếm -->
            <form action="" method="get" class="d-flex align-items-center custom-filter-item">
                <div class="custom-input-group">
                    <input type="text" class="custom-form-control" name="nhap" placeholder="Tìm kiếm sản phẩm..."
                        aria-label="Search">
                    <button class="custom-btn custom-btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

              <!-- Form Lọc danh mục -->
        <form action="" method="get" class="d-flex">
        <div class="input-group">
        @php
    $categoryIds = []; // Mảng lưu các category_id đã duyệt
@endphp
                <select class="form-select form-control" name="filter" aria-label="Lọc sản phẩm theo danh mục">
                    <option value="">Lựa chọn danh mục...</option>
                    @foreach ($products as $index => $product)
        @if (!in_array($product->category->category_id, $categoryIds)) <!-- Kiểm tra nếu category_id chưa xuất hiện -->
            @php
                $categoryIds[] = $product->category->category_id; // Thêm category_id vào mảng
            @endphp
            <option value="{{ $product->category->category_id }}">{{ $product->category->name }}</option>
        @endif
    @endforeach
                </select>
                <button class="btn btn-primary"  type="submit">
                    Lọc</button>
            </div>
        </form>
            <!-- Button Bỏ Áp dụng -->
            <a href="{{ route('admin.products.index') }}" class="btn ml-3">
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
                    <th>Hình ảnh</th>
                    <th>Tên </th>
                    <th>Mã</th>
                    <th>Danh mục</th>
                    <!-- <th>Thương hiệu</th> -->
                    <th>Nổi bật </th>
                    <th>Bán chạy </th>
                    <th>Hoạt động </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ Storage::url($product->main_image_url) }}" alt="Sản phẩm"
                            onerror="this.onerror=null; this.src='{{ asset('imagePro/icon/icon-no-image.png') }}';">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name }}</td>
                    <!-- <td>{{ $product->brand->name }}</td> -->

                    <td>
                        <form action="{{ route('admin.products.toggleHot', $product->product_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="custom-btn-active-admin {{ $product->is_hot ? 'btn-success' : 'btn-danger' }}">
                                <p>{{ $product->is_hot ? 'Đã bật' : 'Đã tắt' }}</p>
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.products.toggleBestSeller', $product->product_id) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="custom-btn-active-admin {{ $product->is_best_seller ? 'btn-success' : 'btn-danger' }}">
                                <p>{{ $product->is_best_seller ? 'Đã bật' : 'Đã tắt' }}</p>
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.products.toggle', $product->product_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="custom-btn-active-admin {{ $product->is_active ? 'btn-success' : 'btn-danger' }}">
                                <p>{{ $product->is_active ? 'Đang bán' : 'Dừng bán' }}</p>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="icon-product d-flex justify-content-center gap-2">
                            <!-- Xem -->
                            <a href="{{ route('admin.products.detail', $product->product_id) }}">
                                <button class="action-btn eye"><i class="fas fa-eye"></i></button>
                            </a>
                            <!-- Sửa -->
                            <a href="{{ route('admin.products.edit', $product->product_id) }}">
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            </a>
                            <!-- Xóa -->
                            <!-- @if(Auth::user()->role !== 3)
                            <form action="{{ route('admin.products.delete', $product->product_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @else
                            @endif -->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-info">
        </div>
        <nav>
            <ul class="pagination">
                {{ $products->links() }}
            </ul>
        </nav>

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
