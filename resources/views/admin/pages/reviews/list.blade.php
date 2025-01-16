@extends('admin.index')

@section('content')

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Danh Sách Đánh giá <i class="fa fa-star"></i></button>
    </div>
    <!-- Modal Add -->
    <div class="modal fade" id="productCreateModal" tabindex="-1" aria-labelledby="productCreateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="button-header">
                        <button>
                            Trả lời Đánh giá <i class="fa fa-star"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <table class="product-table table table-bordered text-center align-middle mb-5">
        <thead class="thead-dark">
            <tr>
                <th></th>
                <th>Email khác hàng</th>
                <th>Tên sản phẩm</th>
                <th>Ảnh</th>
                <th>Đánh giá</th>
                <th>Nội dung</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $review->user->email }}</td>
                <td>{{ $review->product->name }}</td>
                <td>
                    @if (!empty($review->product->main_image_url))
                    <img src="{{ asset('storage/'. $review->product->main_image_url) }}"
                    style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                    @endif
                </td>
                <td>{{ $review->rating }} ★</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->created_at }}</td>

                <td>
                    <form action="{{ route('admin.reviews.toggle', $review->review_id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button type="submit"
                            class="custom-btn-active-admin {{ $review->is_active ? 'btn-success' : 'btn-danger' }} status-btn-active">
                            <p>{{ $review->is_active ? 'Hiển thị' :  'Ẩn'}}</p>
                        </button>
                    </form>
                </td>
                <td>
                    <div class="icon-product d-flex justify-content-center gap-2">
                        <!-- <a href="{{ route('admin.reviews.detail', $review->review_id) }}" class="text-info">
                            <button class="action-btn eye" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                        </a> -->
                        <a href="{{ route('admin.reviews.reply', $review->review_id) }}"
                            data-id="{{ $review->review_id }}" class="text-info">
                            <button class="action-btn reply" title="Trả lời tin nhắn">
                                <i class="fas fa-reply"></i>
                            </button>
                        </a>
                        @if(Auth::user()->role !== 3)
                        <form action="{{ route('admin.reviews.delete', $review->review_id) }}" method="POST"
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
    <nav>
        <ul class="pagination">
            {{ $reviews->links() }}
        </ul>
    </nav>
</div>
<script>
$(document).ready(function() {
    $('.reply').on('click', function(e) {
        e.preventDefault();
        let productId = $(this).closest('a').data('id');

        $('#modalContent').html('<p>Đang tải...</p>');
        $('#productCreateModal').modal('show');

        $.ajax({
            url: `/admin/reviews/comments/${productId}/reply`,
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

$(document).ready(function() {
    // Đóng modal thêm mới
    $('#productCreateModal .btn-close').on('click', function() {
        $('#productCreateModal').modal('hide');
    });
});
</script>

<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush

@endsection
