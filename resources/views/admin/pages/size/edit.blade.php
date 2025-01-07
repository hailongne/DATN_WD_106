@extends('admin.index')

@section('content')
<div class="container mt-5">
    <!-- Tiêu đề -->
    <div class="button-header">
        <button>Chỉnh sửa Kích Thước <i class="fa fa-star"></i></button>
    </div>
<form action="{{route('admin.sizes.update', $size->size_id)}}" method="POST" class="custom-form-container" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name" class="custom-label">Tên kích thước <span class="custom-required-star">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{$size->name}}"
            placeholder="Nhập tên thương hiệu"  />
            @error('name')
<span class="text-danger">{{$message}}</span>
@enderror
    </div>
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.sizes.index') }}" class="btn btn-info">Hủy</a>
    </div>
</form>
</div>
<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
