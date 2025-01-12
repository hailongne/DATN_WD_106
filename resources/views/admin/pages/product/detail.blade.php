@extends('admin.index')
@section('content')
<style>
.description-header h3 {
    font-size: 14px;
    color: #333;
    border-bottom: 2px solid #333;
    padding-bottom: 5px;
    margin-bottom: 15px;
}

.product-description {
    width: 80%;
    margin-left: 100px;
}

.product-description .text-muted {
    max-height: 150px;
    overflow-y: auto;
    padding-left: 10px;
    font-size: 14px;
    scrollbar-color: black;
    scrollbar-width: thin;
}

.card.mb-4.shadow-sm.border-0 {
    border: 2px solid #dee2e6 !important;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}
</style>
<div class="container">

    <!-- Product Info -->
    <div class="card mb-4 mt-2 shadow-sm border-0">
        <div class="button-header mb-3 mt-2">
            <button>
                Chi tiết sản phẩm <i class="fa fa-star"></i>
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-info">Quay lại</a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Ảnh sản phẩm -->
                <div class="col-md-4 text-center">
                    <img src="{{ Storage::url($attPros->first()->product->main_image_url ?? 'default/no-image.jpg') }}"
                        alt="Ảnh sản phẩm" class="img-fluid rounded shadow-sm mb-3 border"
                        style="max-height: 200px; object-fit: cover;">
                </div>
                <!-- Thông tin sản phẩm -->
                <div class="col-md-8">
                    <ul class="list-unstyled mb-0">
                        <li class="fs-5 fw-bold text-dark">
                            {{ $attPros->first()->product->name ?? 'N/A' }}
                        </li>
                        <li class="mb-2">
                            <span>{{ $attPros->first()->product->subtitle ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Mã sản phẩm:</span>
                            <span>{{ $attPros->first()->product->sku ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Trạng thái:</span>
                            @if ($attPros->first()->product->is_active)
                            <span class="badge bg-success">Đang bán</span>
                            @else
                            <span class="badge bg-danger">Dừng bán</span>
                            @endif
                        <li class="mb-2">
                            <span class="text-muted">Phân loại:</span>
                            <span
                                class="badge {{ $attPros->first()->product->is_hot ? 'bg-success' : 'bg-danger' }}">Hot</span>
                            <span
                                class="badge {{ $attPros->first()->product->is_best_seller ? 'bg-success' : 'bg-danger' }}">Bán
                                chạy</span>
                        </li>
                        <!-- <li class="mb-2">
                            <span class="text-muted">Đường dẫn:</span>
                            <span>{{ $attPros->first()->product->slug ?? 'N/A' }}</span>
                        </li> -->
                    </ul>
                </div>
            </div>

            <div class="product-description">
                <div class="description-header">
                    <h3>Mô tả sản phẩm:</h3>
                </div>
                <div class="text-muted">
                    @php
                    $description = $attPros->first()->product->description ?? 'Chưa có mô tả.';
                    // Xử lý Markdown thủ công
                    $description = nl2br(e($description)); // Chuyển đổi xuống dòng
                    $description = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $description);
                    //In đậm
                    $description = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $description); // In nghiêng
                    @endphp
                    {!! $description !!}
                </div>
            </div>
            <div class="card-body">
                <table class="product-table table table-bordered text-center align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <h6>Danh sách thuộc tính</h6>
                        </tr>
                        <tr>
                            <th>Kích cỡ</th>
                            <th>Màu sắc</th>
                            <th>Số lượng </th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attPros as $attPro)
                        <tr>
                            <td>{{ $attPro->size->name }}</td>
                            <td>{{ $attPro->color->name }}</td>
                            <td>{{ $attPro->in_stock }}</td>
                            <td>{{ $attPro->price }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Không có dữ liệu thuộc tính nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection