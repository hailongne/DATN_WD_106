@extends('admin.index')

@section('content')

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Danh Sách Thương Hiệu <i class="fa fa-star"></i></button>
    </div>

    @if(Auth::user()->role !== 3)
    <a href="{{route('admin.brands.create')}}" class="btn add-button">Thêm mới</a>
    @else
    @endif
    <div class="modal fade" id="productCreateModal" tabindex="-1" aria-labelledby="productCreateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="button-header">
                        <button>
                            Thêm mới Kích Thước <i class="fa fa-star"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">✖</button>
                </div>
                <div class="modal-body">
                    <!-- AJAX nội dung sẽ được load tại đây -->
                    <div id="modalContent">
                        <p>Đang tải...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="product-table table table-bordered text-center align-middle">
        <thead class="thead-dark">
            <tr>
                <th>STT</th>
                <th>Tên Thương Hiệu</th>
                <th>Mô Tả</th>
                <th>Trạng thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
            <tr>
                <td>{{ $brand->brand_id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->description }}</td>
                <td>
                    <form action="{{ route('admin.brands.toggle', $brand->brand_id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button type="submit"
                            class="custom-btn-active-admin {{ $brand->is_active ? 'btn-danger' : 'btn-success' }} status-btn-active">
                            <p>{{ $brand->is_active ? 'Tắt hoạt động' : 'Kích hoạt' }}</p>
                        </button>
                    </form>
                </td>
                <td>
                    <div class="icon-product d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.brands.detail', $brand->brand_id) }}" class="text-info">
                            <button class="action-btn eye" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                        </a>
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
<script>
$(document).ready(function() {
    $('.btn-close').on('click', function() {
        $('#productCreateModal').modal('hide');
    });

    $('.add-button').on('click', function(e) {
        e.preventDefault();
        $('#modalContent').html('<p>Đang tải...</p>');
        $('#productCreateModal').modal('show');

        $.ajax({
            url: "{{route('admin.brands.create')}}",
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
            },
            error: function() {
                $('#modalContent').html('<p>Lỗi! Không thể tải nội dung.</p>');
            }
        });
    });
});
</script>

<!-- Scripts -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush

@endsection