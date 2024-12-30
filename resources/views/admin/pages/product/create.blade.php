@extends('admin.index')
@section('content')
@push('styles')
<style>
    /* CSS */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px; /* Khoảng cách giữa các form */
    margin-top: 30px; /* Khoảng cách trên */
}

</style>
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
@endpush

<div class="container mt-5">
<form  action="{{route('admin.products.store')}}" id="productForm" method="POST"  enctype="multipart/form-data">
    @csrf
    @method("POST")
    <!-- Image -->
    <div class="row gx-2 mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <!-- Image Preview Area -->
                <div class="img-container me-3">
                    <img src="#" alt="Preview" id="imagePreview" />
                    <span id="noImageText">Ảnh sản phẩm</span>
                </div>
                <!-- Upload Button -->
                <button type="button" class="custom-btn-upload-admin"
                    onclick="document.getElementById('productImage').click();">
                    <i class="bi bi-upload"></i> <span class="ms-2">Tải lên</span>
                </button>
                <input type="file" class="form-control-file d-none" id="productImage" name="main_image_url"
                    accept="image/*" onchange="showImage(event)" />
            </div>
        </div>
    </div>
    @error('main_image_url')
                    <span class="text-danger">{{$message}}</span>
                @enderror
    <!-- First Row -->
    <div class="row gx-2 mb-3">
        <div class="col-md-6">
            <label class="custom-label" for="productName">Tên sản phẩm</label>
            <input type="text" class="form-control" id="productName" name="name" placeholder="Nhập tên sản phẩm"
                maxlength="50" />
                @error('name')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
      
        <div class="col-md-6">
            <label class="custom-label" for="productSKU">Mã sản phẩm</label>
            <input type="text" class="form-control" id="productSKU" name="sku" placeholder="Nhập mã sản phẩm" 
                maxlength="50" />
                @error('sku')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
      
    </div>

    <div class="row gx-2 mb-3">
        <div class="col-12">
            <label for="productSubtitle">Chú thích sản phẩm</label>
            <input type="text" class="form-control" id="productSubtitle" name="subtitle"
                placeholder="Nhập Chú thích sản phẩm"  maxlength="50" />
        </div>
        @error('subtitle')
                    <span class="text-danger">{{$message}}</span>
                @enderror
    </div>
  
    <!-- Second Row -->
    <div class="row gx-2 mb-3">
        <div class="col-md-6">
            <label class="custom-label" for="productCategory">Danh mục sản phẩm</label>
            <select class="form-control" id="productCategory" name="product_category_id" >
                <option value="0">Chọn danh mục sản phẩm</option>
                @foreach($categories as $category)
                <option value="{{ $category['category_id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
            @error('product_category_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
       
        <div class="col-md-6">
            <label class="custom-label" for="productBrand">Thương hiệu sản phẩm</label>
            <select class="form-control" id="productBrand" name="brand_id" >
                <option value="">Chọn thương hiệu sản phẩm</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->brand_id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('brand_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
        
    </div>
    <!-- Third Row -->
    <div class="row gx-2 mb-3">
        <!-- Dropdown Kích thước sản phẩm -->
        <div class="col-6">
            <label class="custom-label">Kích thước sản phẩm</label>
            <div class="checkbox-group size-group">
                <input type="checkbox" id="selectAllSizes" onclick="toggleAll('size')">
                <label for="selectAllSizes">Chọn tất cả</label>
                <hr />
                @foreach($sizes as $size)
                <div>
                    <input type="checkbox" id="size{{ $size->id }}" name="size_id[]" value="{{ $size->size_id }}">
                    <label for="size{{ $size->id }}">{{ $size->name }}</label>
                </div>
                @endforeach
            </div>
            @error('size_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>

        <!-- Dropdown Màu sắc sản phẩm -->
        <div class="col-6">
            <label class="custom-label">Màu sắc sản phẩm</label>
            <div class="checkbox-group color-group">
                <input type="checkbox" id="selectAllColors" onclick="toggleAll('color')">
                <label for="selectAllColors">Chọn tất cả</label>
                <hr />
                @foreach($colors as $color)
                <div>
                    <input type="checkbox" id="color{{ $color->color_id }}" name="color_id[]"
                        value="{{ $color->color_id }}">
                    <label for="color{{ $color->color_id }}">{{ $color->name }}</label>
                </div>
                @endforeach
            </div>
            @error('color_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
    </div>

    <!-- Fifth Row -->
    <div class="row gx-2 mb-3">
        <div class="col-12">
            <label class="custom-label" for="productDescription">Mô tả sản phẩm</label>
            <textarea class="form-control" id="productDescription" name="description" placeholder="Nhập mô tả sản phẩm"
                rows="5"  maxlength="255" ></textarea>
                @error('description')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
    </div>

    <!-- Buttons -->
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Tiếp tục</button>
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

function toggleAll(type) {
    console.log("Toggled type:", type);

    let idToFind;
    if (type === 'size') {
        idToFind = 'selectAllSizes';
    } else if (type === 'color') {
        idToFind = 'selectAllColors';
    }

    console.log("Trying to find id:", idToFind);

    const selectAllCheckbox = document.getElementById(idToFind);

    if (!selectAllCheckbox) {
        console.log("Không tìm thấy checkbox 'select all'. Kiểm tra id.");
        return;
    }

    const checkboxes = document.querySelectorAll(`.${type}-group input[type="checkbox"]:not(#${idToFind})`);
    console.log("Checkboxes found:", checkboxes);

    const isChecked = selectAllCheckbox.checked;

    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    console.log(document.getElementById(idToFind));

}
</script>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @endpush
@endsection