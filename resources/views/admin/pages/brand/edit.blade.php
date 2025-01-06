@extends('admin.index')

@push('styles')
<link rel="stylesheet" href="{{asset('css/cssSearch.css')}}">
@endpush
@section('content')
<div class="container mt-5 ">
    <div class="button-header">
        <button>Chỉnh sửa thương hiệu <i class="fa fa-star"></i></button>
    </div>
    <form action="{{ route('admin.brands.update', $detailBrand->brand_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="custom-label">Tên thương hiệu <span class="required">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $detailBrand->name }}"
                placeholder="Nhập tên thương hiệu" />
        </div>
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror

        <div class="form-group">
            <label for="slug" class="custom-label">Tên đường dẫn <span class="required">*</span></label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $detailBrand->slug }}"
                placeholder="Nhập tên đường dẫn" />
        </div>
        @error('slug')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-group">
            <label for="description" class="custom-label">Mô tả <span class="required">*</span></label>
            <textarea class="form-control" id="description" name="description" rows="3"
                placeholder="Nhập mô tả">{{ $detailBrand->description }}</textarea>
        </div>
        @error('description')
        <span class="text-danger">{{$message}}</span>
        @enderror

        <div class="button-container float-end">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-info">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>
<!-- Scripts -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush

@endsection
