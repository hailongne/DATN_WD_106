@extends('admin.index')
@section('content')
<style>
.img-container,
.img-container-edit {
    width: 500px;
    height: 125px;
    margin-bottom: 10px;
    border: 1px dashed #6c757d;
    justify-content: center;
    align-items: center;
    color: #6c757d;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    position: relative;
}

.img-container img {
    width: 500px;
    height: 125px;
    object-fit: cover;
    display: none;
    position: absolute;
}

.img-container-edit img {
    width: 500px;
    height: 125px;
    object-fit: cover;
    display: none;
    position: absolute;
}

.img-container span {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: block;
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 5px;
}

.img-container img.show {
    display: block;
}

.img-container:hover {
    background-color: #e9ecef;
}
</style>
<div class="container mb-5">
    <div class="button-header">
        <button>
            Thêm mới danh mục <i class="fa fa-star"></i>
        </button>
    </div>
    <form action="{{route('admin.categories.store')}}" method="POST" class="custom-form-container"
        enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <div class="d-flex align-items-center">
                <!-- Image Preview Area -->
                <div class="img-container mr-3">
                    <img src="{{old('image')}}" alt="Ảnh danh mục" id="imagePreview" />
                    <span id="noImageText">Ảnh danh mục</span>
                </div>

                <!-- Upload Button -->
                <button type="button" class="custom-btn-upload-admin"
                    onclick="document.getElementById('image').click();">
                    <i class="bi bi-upload"></i> <span class="ml-2"> Tải lên</span>
                </button>
                <input type="file" class="form-control-file d-none" id="image" name="image"
                    accept="image/*" onchange="showImage(event)" />
            </div>
            @error('image')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="custom-label" for="name">Tên danh mục <span class="custom-required-star">*</span></label>
            <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name"
                placeholder="Nhập tên danh mục" />
        </div>
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-group">
            <label class="custom-label" for="slug">Tên đường dẫn <span class="custom-required-star">*</span></label>
            <input type="text" class="form-control" value="{{old('slug')}}" id="slug" name="slug"
                placeholder="Nhập tên đường dẫn" />
        </div>
        @error('slug')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-group">
            <label class="custom-label" for="productCategory">Danh mục chính</label>
            <select class="form-control" id="productCategory" value="{{old('parent_id')}}" name="parent_id">
                <option value="0">Chọn danh mục chính </option>
                @foreach($categories as $category)
                <option value="{{ $category['category_id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>
        @error('parent_id')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-group">
            <label class="custom-label" for="description">Mô tả<span class="custom-required-star">*</span></label>
            <textarea class="form-control" id="description" value="{{old('description')}}" rows="3" name="description"
                placeholder="Nhập mô tả"></textarea>
        </div>
        @error('description')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-info">Hủy</a>
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
    } else {
        preview.src = "#";
        preview.classList.remove("show");
        noImageText.style.display = "block";
    }
}
</script>
<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


@endsection