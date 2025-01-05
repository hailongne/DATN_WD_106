@extends('admin.index')
@section('content')
@push('styles')
<style>
/* CSS */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px;
    /* Khoảng cách giữa các form */
    margin-top: 30px;
    /* Khoảng cách trên */
}
</style>
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
@endpush
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
<div class="container">
    <form id="productForm" method="POST" action="{{route('admin.products.update', $product->product_id)}}"
        class="custom-form-container" enctype="multipart/form-data">
        <div class="button-header mb-3">
            <button>
                Chỉnh sửa sản phẩm <i class="fa fa-star"></i>
            </button>
            <div class="custom-btn-action d-flex p2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-info btn-sm mr-1"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Hủy</a>
                <button type="submit" class="btn btn-primary btn-sm"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Tiếp
                    tục</button>
            </div>
        </div>
        @csrf
        @method("PUT")
        <div class="row gx-2 mb-3">
            <div class="col-3 mr-5">
                <div class="d-flex align-items-center">
                    <!-- Image Preview Area -->
                    <div class="img-container-edit me-3">
                        <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="No Image"
                            id="imagePreview" />
                    </div>
                    <!-- Upload Button -->
                    <button type="button" class="custom-btn-upload-admin"
                        onclick="document.getElementById('productImage').click();">
                        <i class="bi bi-upload"></i> <span class="ms-2">Tải lên</span>
                    </button>
                    <input type="file" class="form-control-file d-none" id="productImage" name="main_image_url"
                        accept="image/*" onchange="showImage(event)" />
                </div>
                @error('main_image_url')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-4 mr-4">
                <div class="mb-4">
                    <label class="custom-label" for="productName">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="productName" name="name" placeholder="Nhập tên sản phẩm"
                        required maxlength="50" value="{{$product->name}}" />
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div>
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
                    @error('product_category_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-4">
                    <label class="custom-label" for="productSKU">Mã sản phẩm</label>
                    <input type="text" class="form-control" id="productSKU" name="sku" placeholder="Nhập mã sản phẩm"
                        required maxlength="50" value="{{$product->sku}}" />
                    @error('sku')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div>
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
                    @error('brand_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row gx-2 mb-5">
            <div class="col-3 mr-5">
                <label class="custom-label" for="productSubtitle">Chú thích sản phẩm</label>
                <input type="text" class="form-control" id="productSubtitle" name="subtitle"
                    placeholder="Nhập Chú thích sản phẩm" maxlength="50" style="height: 52px;"
                    value="{{$product->subtitle}}" />
            </div>
            @error('subtitle')
            <span class="text-danger">{{$message}}</span>
            @enderror

            <div class="col-4 mr-4 accordion" id="sizeAccordion">
                <label class="custom-label" for="productDescription">Kích thước</label>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Kích thước sản phẩm
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#sizeAccordion">
                        <div class="accordion-body">
                            <div>
                                <input type="checkbox" id="selectAllSizes" onclick="toggleAllSizes(this)">
                                <label for="selectAllSizes"><strong>Chọn tất cả</strong></label>
                            </div>
                            <hr />
                            @foreach($sizes as $size)
                            <div>
                                <input type="checkbox" id="size{{ $size->id }}" name="size_id[]"
                                    value="{{ $size->size_id }}" @if(in_array($size->size_id, old('size_id',
                                $product->sizes->pluck('size_id')->toArray())))
                                checked @endif
                                />
                                <label for="size{{ $size->size_id }}">{{ $size->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dropdown Màu sắc sản phẩm -->
            <div class="col-4 accordion" id="colorAccordion">
                <label class="custom-label" for="productDescription">Màu sắc</label>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingColors">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseColors" aria-expanded="true" aria-controls="collapseColors">
                            Màu sắc sản phẩm
                        </button>
                    </h2>
                    <div id="collapseColors" class="accordion-collapse collapse show" aria-labelledby="headingColors">
                        <div class="accordion-body">
                            <!-- Checkbox chọn tất cả -->
                            <div>
                                <input type="checkbox" id="selectAllColors" onclick="toggleAllColors(this)">
                                <label for="selectAllColors"><strong>Chọn tất cả</strong></label>
                            </div>
                            <hr />
                            <!-- Danh sách các màu -->
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
            </div>
        </div>

        <!-- Select filter -->
        <div class="row gx-2 mb-3">
            <div class="col-md-12">
                <label class="custom-label">Lọc sản phẩm theo phân loại</label>
            </div>
            <div class="col-md-4">
                <label for="is_active">Sản phẩm hoạt động</label>
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ $product->is_active ? 'checked' : '' }}>
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
                <textarea class="form-control" id="productDescription" name="description"
                    placeholder="Nhập mô tả sản phẩm" rows="5"
                    required>{{ old('description', $product->description) }}</textarea>
            </div>
            @error('description')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <!-- Preview Row -->
        <div class="row gx-2 mb-3">
            <div class="col-12">
                <label class="custom-label">Xem trước mô tả:</label>
                <div id="descriptionPreview" class="p-3 border rounded bg-light">
                    <em>Nội dung xem trước sẽ hiển thị ở đây...</em>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionInput = document.getElementById('productDescription');
            const descriptionPreview = document.getElementById('descriptionPreview');

            function updatePreview() {
                // Lấy giá trị từ textarea
                let description = descriptionInput.value;

                // Chuyển đổi Markdown-like syntax thành HTML
                let formattedText = description
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // In đậm
                    .replace(/\*(.*?)\*/g, '<em>$1</em>') // In nghiêng
                    .replace(/\n/g, '<br>'); // Xuống dòng

                // Cập nhật nội dung xem trước
                descriptionPreview.innerHTML = formattedText;
            }

            // Cập nhật xem trước khi gõ phím
            descriptionInput.addEventListener('input', updatePreview);

            // Hiển thị xem trước ban đầu (nếu có)
            updatePreview();
        });
        </script>
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

function toggleAllColors(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.color-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function toggleAllSizes(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.size-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
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