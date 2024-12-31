@extends('admin.index')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{asset('css/admin/formAddCategory.css')}}">
<style>
.cusstom-no-image {
    width: 50px !important;
    height: 50px !important;
}
</style>
@endpush
<div class="container mt-5 ">
<div class="button-header">
        <button>
            Chỉnh sửa danh mục <i class="fa fa-star"></i>
        </button>
    </div>
<form action="{{ route('admin.categories.update', $category->category_id) }}" method="POST"
    class="custom-form-container" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name" class="custom-label">Tên danh mục <span class="custom-required-star">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}"
            placeholder="Nhập tên thương hiệu"  />
    </div>
    @error('name')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="form-group">
        <label for="description" class="custom-label">Mô tả <span class="custom-required-star">*</span></label>
        <textarea class="form-control" id="description" name="description" rows="3"
            placeholder="Nhập mô tả">{{ $category->description }}</textarea>
    </div>
    @error('description')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="form-group">
        <label for="productCategory" class="custom-label">Chọn danh mục cha</label>
        <select class="form-control" id="category" name="parent_id">
            <option value="0">Chọn danh mục cha</option>
            @foreach($categories as $subCategory)
            <option value="{{ $subCategory['category_id'] }}" @if(old('parent_id', $category->parent_id) ==
                $subCategory['category_id']) selected @endif>
                {{ $subCategory['name'] }}
            </option>
            @endforeach
        </select>

    </div>
    @error('parent_id')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="form-group">
        <div class="d-flex align-items-center">
            <!-- Image Preview Area -->
            <div class=" mr-3">
                <img src="{{Storage::url($category->image)}}" width="500px" height="300px" alt="Ảnh danh mục" id="imagePreview" />
                <span id="noImageText">Ảnh danh mục</span>
            </div>
            <!-- Upload Button -->
            <button type="button" class="custom-btn-upload-admin" onclick="document.getElementById('image').click();">
                <i class="bi bi-upload"></i> <span class="ml-2"> Tải lên</span>
            </button>
            <input type="file" class="form-control-file d-none" id="image" name="image" accept="image/*"
                onchange="showImage(event)" />
        </div>
    </div>
    @error('image')
<span class="text-danger">{{$message}}</span>
@enderror

    <div class="button-group">
        <button type="submit" class="btn btn-primary">Lưu</button>
    </div>
</form>
</div>
<script>
function showImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("imagePreview");
    const noImageText = document.getElementById("noImageText");

    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            preview.src = reader.result;
            preview.classList.add("show");
            noImageText.style.display = "none";
        };
        reader.readAsDataURL(file);
    }
}
</script>
</script>
<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


@endsection