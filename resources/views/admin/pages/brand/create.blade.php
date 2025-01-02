@extends('admin.index')

@push('styles')
<link rel="stylesheet" href="{{asset('css/cssSearch.css')}}">
@endpush
@section('content')
<div class="container mt-5 ">
<div class="button-header">
        <button>Thêm mới thương hiệu <i class="fa fa-star"></i></button>
    </div>
<form action="{{route('admin.brands.store')}}" method="POST" class="custom-form-container"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="name" class="custom-label">Tên thương hiệu <span class="custom-required-star">*</span></label>
        <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name" placeholder="Nhập tên thương hiệu"  />
    </div>
    @error('name')
<span class="text-danger">{{$message}}</span>
@enderror

    <div class="form-group">
        <label for="slug" class="custom-label">Tên đường dẫn <span class="custom-required-star">*</span></label>
        <input type="text" class="form-control" id="slug" value="{{old('slug')}}" name="slug" placeholder="Nhập tên đường dẫn"  />
    </div>
    @error('slug')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="form-group">
        <label for="description" class="custom-label">Mô tả <span class="custom-required-star">*</span></label>
        <textarea class="form-control" id="description"  value="{{old('description')}}" rows="3" name="description" placeholder="Nhập mô tả"
            ></textarea>
    </div>
    @error('description')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Thêm mới</button>
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