@extends('admin.index')

<style>
.btn-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 0.9rem;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-circle:hover {
    transform: scale(1.1);
}

#description {
    font-size: 1rem;
    color: #333;
}

.d-flex {
    display: flex;
    align-items: center;
}
</style>
@section('content')
<div class="container mt-5">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Chi tiết Kích Thước <i class="fa fa-star"></i></button>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>
                <span id="name">{{ $color->name }}</span>
            </h3>
            <hr>
            <p>
                <strong>Mã màu:</strong>
            <p class="d-flex align-items-center">
                <span>
                    <button class="btn-circle" style="background-color: {{ $color->color_code }};"></button>
                </span>
                <span id="description" class="ml-2">{{ $color->color_code }}</span>
            </p>
            </p>
            <p>
                <strong>Ngày Tạo:</strong>
                <span id="created_at">{{ \Carbon\Carbon::parse($color->created_at)->format('d/m/Y H:i') }}</span>
            </p>
            <p>
                <strong>Ngày Cập Nhật:</strong>
                <span id="updated_at">{{ \Carbon\Carbon::parse($color->updated_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
        <div class="button-group">
            <a href="{{ route('admin.colors.index') }}" class="btn btn-info mb-2">Quay lại</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

@push('scripts')
@endpush

@endsection