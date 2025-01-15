@extends('admin.index')

@push('styles')
<link rel="stylesheet" href="{{asset('css/cssSearch.css')}}">
@endpush
@section('content')

<div class="container">
    <!-- Tiêu đề -->
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
        <button>Danh sách thương hiệu <i class="fa fa-star"></i></button>
        @if(Auth::user()->role !== 3)
        <a href="{{route('admin.brands.create')}}" class="btn add-button">Thêm mới</a>
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
        <a href="{{ route('admin.brands.index') }}" class="btn ml-3">
            <image src="{{ asset('imagePro/icon/icon-remove-filter.png') }}" style="width: 35px" />
        </a>
    </div>
    @php
    $index = 1; // Bắt đầu đếm từ 1
    @endphp
    <table class="product-table table table-bordered text-center align-middle mb-5">
        <thead class="thead-dark">
            <tr>
                <th></th>
                <th>Tên Thương Hiệu</th>
                <th>Mô Tả</th>
              
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
            <tr>
                <td>{{ $index++ }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->description }}</td>
                <td>
                    <div class="icon-product d-flex justify-content-center gap-2">
                        <!-- <a href="{{ route('admin.brands.detail', $brand->brand_id) }}" class="text-info">
                            <button class="action-btn eye" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                        </a> -->
                        <a href="{{ route('admin.brands.edit', $brand->brand_id) }}" class="text-warning">
                            <button class="action-btn edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                        </a>
                        @if(Auth::user()->role !== 3)
                        <form action="{{ route('admin.brands.delete', $brand->brand_id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?');"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete">
                                <i class="fas fa-trash-alt" title="Xóa"></i>
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
            {{ $brands->links() }}
        </ul>
    </nav>
</div>


<!-- Scripts -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush

@endsection
