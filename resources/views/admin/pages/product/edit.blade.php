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
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
<div class="container mt-5">
<form id="productForm" method="POST" action="{{route('admin.products.update', $product->product_id)}}"
    class="custom-form-container" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <!-- Image -->
    <div class="row gx-2 mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <!-- Image Preview Area -->
                <div class="img-container-edit me-3">
                    <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="No Image" id="imagePreview" />
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

    <!-- First Row -->
    <div class="row gx-2 mb-3">
        <div class="col-md-6">
            <label class="custom-label" for="productName">Tên sản phẩm</label>
            <input type="text" class="form-control" id="productName" name="name" placeholder="Nhập tên sản phẩm"
                required maxlength="50" value="{{$product->name}}" />
        </div>
        <div class="col-md-6">
            <label class="custom-label" for="productSKU">Mã sản phẩm</label>
            <input type="text" class="form-control" id="productSKU" name="sku" placeholder="Nhập mã sản phẩm" required
                maxlength="50" value="{{$product->sku}}" />
        </div>
    </div>

    <div class="row gx-2 mb-3">
        <div class="col-12">
            <label for="productSubtitle">Chú thích sản phẩm</label>
            <input type="text" class="form-control" id="productSubtitle" name="subtitle"
                placeholder="Nhập Chú thích sản phẩm" required maxlength="50" value="{{$product->subtitle}}" />
        </div>
    </div>

    <!-- Second Row -->
    <div class="row gx-2 mb-3">
        <div class="col-md-6">
            <label class="custom-label" for="productCategory">Danh mục sản phẩm</label>
            <select class="form-control" id="productCategory" name="product_category_id" required>
                <option value="0">Chọn danh mục sản phẩm</option>
                @foreach($categories as $category)
                <option value="{{ $category['category_id'] }}" @if(old('product_category_id', $product->
                    product_category_id) == $category['category_id']) selected @endif>
                    {{ $category['name'] }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="custom-label" for="productBrand">Thương hiệu sản phẩm</label>
            <select class="form-control" id="productBrand" name="brand_id" required>
                <option value="">Chọn thương hiệu sản phẩm</option>
                @foreach($brands as $brand)
                @if ($product->brand_id == $brand->brand_id)
                <option value="{{ $brand->brand_id }}" selected>{{ $brand->name }}</option>
                @else
                <option value="{{ $brand->brand_id }}">{{ $brand->name }}</option>
                @endif
                @endforeach
            </select>
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
                    <input type="checkbox" id="size{{ $size->id }}" name="size_id[]" value="{{ $size->size_id }}"
                        @if(in_array($size->size_id, old('size_id', $product->sizes->pluck('size_id')->toArray())))
                    checked @endif
                    />
                    <label for="size{{ $size->size_id }}">{{ $size->name }}</label>
                </div>
                @endforeach
            </div>
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
                        value="{{ $color->color_id }}" @if(in_array($color->color_id, old('color_id',
                    $product->colors->pluck('color_id')->toArray()))) checked @endif />
                    <label for="color{{ $color->color_id }}">{{ $color->name }}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Select filter -->
    <div class="row gx-2 mb-3">
        <div class="col-md-12">
            <label class="custom-label">Lọc sản phẩm theo phân loại</label>
        </div>
        <div class="col-md-4">
            <label for="is_active">Sản phẩm hoạt động</label>
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
        </div>

        <div class="col-md-4">
            <label for="is_hot">Sản phẩm hot</label>
            <input type="checkbox" name="is_hot" id="is_hot" value="1" {{ $product->is_hot ? 'checked' : '' }}>
        </div>

        <div class="col-md-4">
            <label for="is_best_seller">Sản phẩm bán chạy</label>
            <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1"
                {{ $product->is_best_seller ? 'checked' : '' }}>
        </div>
    </div>

    <!-- Fifth Row -->
    <div class="row gx-2 mb-3">
        <div class="col-12">
            <label class="custom-label" for="productDescription">Mô tả sản phẩm</label>
            <textarea class="form-control" id="productDescription" name="description" placeholder="Nhập mô tả sản phẩm"
                rows="5" required maxlength="255">{{ $product->description }}</textarea>
        </div>
    </div>

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