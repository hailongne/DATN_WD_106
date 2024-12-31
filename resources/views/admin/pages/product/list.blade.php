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
@endpush

    <body>
        <div class="container mt-2">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

            <div class="button-header">
                <button>
                    Danh sách sản phẩm <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="container mt-5">
    <div class="form-container">
        <!-- Form Tìm kiếm -->
        <form action="" method="get" class="d-flex">
            <div class="input-group">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> <!-- Icon tìm kiếm -->
                </button>
                <input
                    type="text"
                    class="form-control"
                    name="nhap"
                    placeholder="Tìm kiếm sản phẩm..."
                    aria-label="Search"
                >
            </div>
        </form>

        <!-- Form Lọc danh mục -->
        <form action="" method="get" class="d-flex">
        <div class="input-group">
                <select class="form-select form-control" name="filter" aria-label="Lọc sản phẩm theo danh mục">
                    <option value="">Lựa chọn danh mục...</option>
                    @foreach ($products as $index => $product)
                        <option value="{{$product->category->category_id}}">{{$product->category->name}}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary"  type="submit">
                    Lọc</button>
            </div>
        </form>
        <!-- form lọc thưogn hiệu -->
        <form action="" method="get" class="d-flex">
        <div class="input-group">
                <select class="form-select form-control" name="brand" aria-label="Lọc sản phẩm theo danh mục">
                    <option value="">Lựa chọn thưogn hiệu...</option>
                    @foreach ($products as $index => $product)
                        <option value="{{$product->brand->brand_id}}">{{$product->brand->name}}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary"  type="submit">
                    Lọc</button>
            </div>
        </form>
    </div>
</div>




            @if(Auth::user()->role !== 3)
            <!-- Kiểm tra nếu không phải manager -->
            <a href="{{route('admin.products.create')}}" class="btn add-button">Thêm mới</a>
            @endif


            <table class="product-table table table-bordered text-center align-middle mb-5">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 5%;">STT</th>
                        <th style="width: 15%;">Hình ảnh</th>
                        <th style="width: 15%;">Tên Sản Phẩm</th>
                        <th style="width: 10%;">Mã</th>
                        <th style="width: 15%;">Danh mục</th>
                        <th style="width: 15%;">Thương hiệu</th>
                        <th style="width: 10%;">Hoạt động </th>
                        <th style="width: 20%;"></th>
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
                                    <p>{{ $product->is_active ? 'Đang hoạt động' : 'Đã tắt hoạt động' }}</p>
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
