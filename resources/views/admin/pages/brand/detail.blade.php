@extends('admin.index')

@push('styles')
<style>
.brand-info {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.brand-info h3 {
    font-size: 1.6rem;
    font-weight: bold;
    color: #333;
}

.brand-info p {
    font-size: 1rem;
    color: #555;
    margin: 5px 0;
}

.btn-success {
    background-color: #28a745;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    color: white;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-success:hover {
    background-color: #218838;
}

.text-center {
    margin-top: 20px;
}
</style>
@endpush

@section('content')

<div class="container mt-5">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Chi tiết Thương Hiệu <i class="fa fa-star"></i></button>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>
                <span id="name">{{$detailBrand->name}}</span>
            </h3>
            <hr>

            <p>
                <strong>Mô Tả:</strong>
                <span id="description">{{$detailBrand->description}}</span>
            </p>
            <p>
                <strong>Tên Đường Dẫn:</strong>
                <span id="slug">{{$detailBrand->slug}}</span>
            </p>
            <p>
                <strong>Ngày Tạo:</strong>
                <span id="created_at">{{ \Carbon\Carbon::parse($detailBrand->created_at)->format('d/m/Y H:i') }}</span>
            </p>
            <p>
                <strong>Ngày Cập Nhật:</strong>
                <span id="updated_at">{{ \Carbon\Carbon::parse($detailBrand->updated_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
        <div class="button-group">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-info mb-2">Quay lại</a>
        </div>
    </div>
</div>
@endsection