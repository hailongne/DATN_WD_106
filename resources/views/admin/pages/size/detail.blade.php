@extends('admin.index')

@section('content')
<div class="container mt-5">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Chi tiết Kích Thước <i class="fa fa-star"></i></button>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">
                Tên size:
                <span id="name">{{$size->name}}</span>
            </h3>
            <hr>
            <p>
                <strong>Ngày Tạo:</strong>
                <span id="created_at">{{ \Carbon\Carbon::parse($size->created_at)->format('d/m/Y H:i') }}</span>
            </p>
            <p>
                <strong>Ngày Cập Nhật:</strong>
                <span id="updated_at">{{ \Carbon\Carbon::parse($size->updated_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
        <div class="button-group">
            <a href="{{ route('admin.sizes.index') }}" class="btn btn-info mb-2">Quay lại</a>
        </div>
    </div>
</div>

<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
