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
                        <i class="bi bi-search"></i> <!-- Icon tìm kiếm -->
                    </button>
                </div>
            </form>

            <!-- Form Áp dụng danh mục -->
            <form action="" method="get" class="d-flex align-items-center custom-filter-item">
                <div class="custom-input-group">
                    <select class="custom-select custom-form-control" name="filter"
                        aria-label="Áp dụng sản phẩm theo danh mục">
                        <option value="">Danh mục...</option>
                        @foreach ($products as $product)
                        <option value="{{$product->category->category_id}}">{{$product->category->name}}</option>
                        @endforeach
                    </select>
                    <button class="custom-btn custom-btn-success" type="submit">Áp dụng</button>
                </div>
            </form>

            <!-- Form Áp dụng Thương hiệu -->
            <form action="" method="get" class="d-flex align-items-center custom-filter-item">
                <div class="custom-input-group">
                    <select class="custom-select custom-form-control" name="brand"
                        aria-label="Áp dụng sản phẩm theo thương hiệu">
                        <option value="">Thương hiệu...</option>
                        @foreach ($products as $product)
                        <option value="{{$product->brand->brand_id}}">{{$product->brand->name}}</option>
                        @endforeach
                    </select>
                    <button class="custom-btn custom-btn-success" type="submit">Áp dụng</button>
                </div>
            </form>
            <!-- Button Bỏ Áp dụng -->
            <a href="{{ route('admin.products.index') }}" class="btn ml-3">
                <image src="{{ asset('imagePro/icon/icon-remove-filter.png') }}" style="width: 35px" />
            </a>

        </div>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 5%;"></th>
                    <th style="width: 15%;">Hình ảnh</th>
                    <th style="width: 15%;">Tên Sản Phẩm</th>
                    <th style="width: 10%;">Mã</th>
                    <th style="width: 15%;">Danh mục</th>
                    <th style="width: 15%;">Thương hiệu</th>
                    <th style="width: 10%;">Hoạt động </th>
                    <th style="width: 20%;">
                    </th>
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
                    <td>{{ $product->brand->name }}</td>
                    <td>
                        <form action="{{ route('admin.products.toggle', $product->product_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="custom-btn-active-admin {{ $product->is_active ? 'btn-success' : 'btn-danger' }}">
                                <p>{{ $product->is_active ? 'Hoàn thành' : 'Đã hủy' }}</p>
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
                            @if(Auth::user()->role !== 3)
                            <form action="{{ route('admin.products.delete', $product->product_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
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