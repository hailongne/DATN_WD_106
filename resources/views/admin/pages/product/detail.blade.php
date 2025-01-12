@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/detailProduct.css') }}">
@endpush
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Chi tiết sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <!-- Product Info -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Thông tin sản phẩm</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!--Ảnh-->
                <div class="col-md-4 text-center">
                    <img src="{{ Storage::url($attPros->first()->product->main_image_url ?? 'default/no-image.jpg') }}"
                        alt="Ảnh sản phẩm"
                        class="img-fluid rounded shadow-sm mb-3"
                        style="max-height: 200px;">
                </div>
                <!-- Thông tin -->
                <div class="col-md-8">
                    <p><strong>Mã sản phẩm:</strong> {{ $attPros->first()->product->sku ?? 'N/A' }}</p>
                    <p><strong>Tên sản phẩm:</strong> {{ $attPros->first()->product->name ?? 'N/A' }}</p>
                    <p><strong>Slug:</strong> {{ $attPros->first()->product->slug ?? 'N/A' }}</p>
                    <p><strong>Subtitle:</strong> {{ $attPros->first()->product->subtitle ?? 'N/A' }}</p>
                    <p><strong>Mô tả:</strong>
                        <span class="text-muted">
                            {{ $attPros->first()->product->description ?? 'Chưa có mô tả.' }}
                        </span>
                    </p>
                    <p><strong>Trạng thái:</strong>
                        @if ($attPros->first()->product->is_active)
                        <span class="badge bg-success">Hoạt động</span>
                        @else
                        <span class="badge bg-danger">Không hoạt động</span>
                        @endif
                    </p>
                    <p><strong>Phân loại:</strong>
                        <span class="badge {{ $attPros->first()->product->is_hot ? 'bg-warning' : 'bg-secondary' }}">Hot</span>
                        <span class="badge {{ $attPros->first()->product->is_best_seller ? 'bg-success' : 'bg-secondary' }}">Bán chạy</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Danh sách thuộc tính</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Kích cỡ</th>
                        <th>Màu sắc</th>
                        <th>Số lượng trong kho</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attPros as $attPro)
                    <tr>
                        <td>{{ $attPro->size->name }}</td>
                        <td>{{ $attPro->color->name }}</td>
                        <td>{{ $attPro->in_stock }}</td>
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
@endsection