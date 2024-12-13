@extends('admin.index')
@section('content')
<style>
.table td.color-code {
    color: #fff;
    font-weight: bold;
}
</style>

<body>
    <div class="container mt-4">
        <div class="button-header">
            <button>
                Danh Sách Màu Sắc <i class="fa fa-star"></i>
            </button>
        </div>

        @if(Auth::user()->role !== 3)
        <a href="{{ route('admin.colors.create') }}" class="btn add-button">Thêm mới</a>
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
                url: "{{ route('admin.colors.create') }}",
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

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    @endpush

    @endsection