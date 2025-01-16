@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{asset('css/admin/editAtrpro.css')}}">
@endpush
@section('content')

<body>
    <div class="container">
        <div class="button-header">
            <button>
                Điều chỉnh thông tin sản phẩm <i class="fa fa-star"></i>
            </button>
        </div>
        <div class="containerEditAtrpro">
            @foreach($groupedBySize as $size => $items)
            <?php
                $sizeString = (string) $size;
                $parts = explode('-', $sizeString);
            ?>
            <table class="product-table table table-bordered text-center align-middle mb-4">
                <thead class="thead-dark">
                    <tr>
                        <td colspan="4" class="text-left size-header-custom">
                            <p class="text-custom">Sản phẩm kích cỡ: {{ $parts[0] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Tên Sản Phẩm</th>
                        <th>Màu sắc</th>
                        <th>Giá </th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody id="product-list">
                    @foreach($items as $item)
                    <tr class="col-4 data-attribute" data-attribute_product_id="{{ $item->attribute_product_id }}">
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->color->name }}</td>
                        <input type="hidden" name="attribute_product_id[]" value="{{ $item->attribute_product_id }}">

                        <td><input type="number" @if(old('price')==$item->price )selected @endif name="price[]"
                            class="form-control price" value="{{ $item->price }}">

                            <span style="color: red" class="price-error"></span>
                        </td>

                        <td><input type="number" name="in_stock[]" @if(old('in_stock')==$item->in_stock )selected @endif
                            class="form-control in-stock" value="{{ $item->in_stock }}"
                            >
                            <span style="color: red" class="in-stock-error"></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">
                            @CSRF
                            <label for="url_{{ $parts[1] }}" class="btn custom-upload-btn-atriPro">
                                <i class="fa fa-upload"></i> Tải lên
                            </label>
                            <input type="file" name="url[]" accept="image/png, image/jpg, image/jpeg, image/gif"
                                id="url_{{ $parts[1] }}" class="d-none url" multiple>

                            <div id="imagePreviewContainer_{{ $parts[1] }}" class="mt-3 d-flex flex-wrap">
                                <!-- Ảnh sẽ hiển thị ở đây -->
                            </div>
                            <span class="text-danger url-error" id="url-error-{{ $parts[1] }}"></span>
                        </td>

            </table>
            @endforeach

            <div class="button-group">
                <button type="submit" id="submitForm" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
   let imagesData = [];

$(document).ready(function() {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(event) {
            const files = event.target.files;
            const sizeId = event.target.id.split('_')[1];
            const previewContainer = document.getElementById('imagePreviewContainer_' +
                colorId);

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('position-relative', 'm-2');
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '130px';
                    img.style.borderRadius = '5px';
                    //Dấu X xóa ảnh
                    const blockRemoveIcon = document.createElement('div');
                    const removeIcon = document.createElement('i');
                    removeIcon.classList.add('fa', 'fa-times-circle', 'position-absolute', 'text-danger');
                    removeIcon.style.top = '5px';
                    removeIcon.style.right = '5px';
                    removeIcon.style.cursor = 'pointer';
                    removeIcon.id = 'svg_id_' + i;
                    blockRemoveIcon.appendChild(removeIcon);

                    // Thêm sự kiện xóa ảnh trực tiếp tại đây
                    blockRemoveIcon.addEventListener('click', function() {
                        imgContainer.remove(); // Xóa phần tử chứa ảnh
                    });
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(blockRemoveIcon);
                    previewContainer.appendChild(imgContainer);
                };

                reader.readAsDataURL(file);
            }
        });
    });

    //check lối price

    // Lấy tất cả các phần tử có class 'price' và 'in-stock'


    // // Lặp qua tất cả các input có class 'price'
    // Array.from(priceInputs).forEach((priceInput, index) => {
    //     priceInput.addEventListener('change', function(event) {
    //         const value = event.target.value;

    //         // Kiểm tra giá trị input price
    //         if (value.trim() === '') {
    //             priceErrors[index].textContent = 'Giá không được để trống';
    //             isFormValid = false;
    //         } else if (isNaN(value) || value <= 0) {
    //             priceErrors[index].textContent = 'Giá phải là số lớn hơn 0';
    //             isFormValid = false;
    //         } else {
    //             priceErrors[index].textContent = ''; // Xóa lỗi nếu hợp lệ'
    //             isFormValid = true;
    //         }
    //     });
    // });

    // Lặp qua tất cả các input có class 'in-stock'
    // Array.from(inStockInputs).forEach((inStockInput, index) => {
    //     inStockInput.addEventListener('change', function(event) {
    //         const value = event.target.value;
    //         // Kiểm tra giá trị input in_stock
    //         if (value.trim() === '') {
    //             inStockErrors[index].textContent = 'Số lượng không được để trống';
    //             isFormValid = false;
    //         } else if (value <= 5) { // Kiểm tra nếu giá trị là số 0
    //             inStockErrors[index].textContent = 'Số lượng phải lớn hơn 5 vì 5 là ngưỡng tồn kho';
    //             isFormValid = false; // Form không hợp lệ
    //         } else if (isNaN(value)) {
    //             inStockErrors[index].textContent =
    //                 'Số lượng phải là số và lớn hơn hoặc bằng 1';
    //             isFormValid = false;

    //         } else if (!Number.isInteger(Number(value))) {
    //             inStockErrors[index].textContent = 'Số lượng phải là số nguyên';
    //             isFormValid = false;

    //         } else if (value == 0) { // Kiểm tra nếu giá trị là số 0
    //             inStockErrors[index].textContent = 'Số lượng phải lớn hơn 0';
    //             isFormValid = false; // Form không hợp lệ
    //         } else {
    //             inStockErrors[index].textContent = ''; // Xóa lỗi nếu hợp lệ
    //             isFormValid = true;
    //         }
    //     });
    // });

    //lặp qua tất cả class url

    // Thêm sự kiện khi click vào nút submit
    $('#submitForm').click(function(event) {
        const priceInputs = document.getElementsByClassName('price');
        const priceErrors = document.getElementsByClassName('price-error');
        const inStockInputs = document.getElementsByClassName('in-stock');
        const inStockErrors = document.getElementsByClassName('in-stock-error');
        const urlInputs = document.getElementsByClassName('url');
        const urlErrors = document.getElementsByClassName('url-error');
        let isFormValid = true;

        //check price
        Array.from(priceInputs).forEach((priceInput, index) => {
        const value = priceInput.value.trim();
        if (value === '') {
            priceErrors[index].textContent = 'Giá không được để trống';
            isFormValid = false;
        } else if (isNaN(value) || value <= 0) {
            priceErrors[index].textContent = 'Giá phải là số lớn hơn 0';
            isFormValid = false;
        } else {
            priceErrors[index].textContent = '';

        }
    });
//check in_stock
 // Lặp qua tất cả các input có class 'in-stock'
 Array.from(inStockInputs).forEach((inStockInput, index) => {
        const value = inStockInput.value.trim();
        if (value === '') {
            inStockErrors[index].textContent = 'Số lượng không được để trống';
            isFormValid = false;
        } else if (value <= 5) { // Kiểm tra ngưỡng tồn kho
            inStockErrors[index].textContent = 'Số lượng phải lớn hơn 5 vì 5 là ngưỡng tồn kho';
            isFormValid = false;
        } else if (isNaN(value)) {
            inStockErrors[index].textContent = 'Số lượng phải là số';
            isFormValid = false;
        } else if (!Number.isInteger(Number(value))) {
            inStockErrors[index].textContent = 'Số lượng phải là số nguyên';
            isFormValid = false;
        } else {
            inStockErrors[index].textContent = ''; // Xóa lỗi nếu hợp lệ

        }
    });


    //check url


        Array.from(urlInputs).forEach((urlInput, index) => {
            const files = urlInput.files;
            const errorElement = urlErrors[index];

            if (files.length === 0) {
                errorElement.textContent = 'Vui lòng chọn ít nhất một ảnh';
                isFormValid = false;
            } else if (files.length > 4) {
                errorElement.textContent = 'Không được chọn quá 4 ảnh';
                isFormValid = false;
            } else {
                let isValid = true;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    const validExtensions = ['png', 'jpg', 'jpeg', 'gif'];

                    if (!validExtensions.includes(fileExtension)) {
                        errorElement.textContent = 'Chỉ chấp nhận file hình ảnh với định dạng PNG, JPG, JPEG, GIF';
                        isValid = false;
                        break;
                    }

                    if (file.size > 5 * 1024 * 1024) {
                        errorElement.textContent = 'Kích thước ảnh không được vượt quá 5MB';
                        isValid = false;
                        break;
                    }
                }

                if (isValid) {
                    errorElement.textContent = '';
                } else {
                    isFormValid = false;
                }
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            alert('Vui lòng sửa các lỗi trước khi gửi form!');
            return;
        }

        imagesData = [];

        $('.send-img').each(function() {
            const sizeId = $(this).data('size-id');
            const files = $(this).find('input[type="file"]')[0].files;
            const imgArray = Array.from(files);
            console.log(colorId);  // Kiểm tra xem colorId có đúng không
            console.log(files);    // Kiểm tra xem files có chứa các file được chọn không
            imagesData.push({
                size_id: sizeId,
                images: imgArray
            });

        });

        const attributesData = [];
        $('.data-attribute').each(function() {
            const attributeProductId = $(this).data('attribute_product_id');
            const prices = $(this).find('input[name="price[]"]').val();
            const inStocks = $(this).find('input[name="in_stock[]"]').val();
            attributesData.push({
                attribute_product_id: attributeProductId,
                prices: prices,
                in_stock: inStocks
            });
        });

        const formData = new FormData();
        formData.append("img", JSON.stringify(imagesData));
        formData.append("attributeProducts", JSON.stringify(attributesData));
        formData.append("product_id", <?php echo $product_id?>);

        imagesData.forEach(img => {
            formData.append('size_id[]', img.size_id);
            img.images.forEach(image => {
                formData.append(`images_${img.size_id}[]`, image);
            });
        });
        console.log(imagesData); // Kiểm tra dữ liệu ảnh gửi đi
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        fetch("/admin/products/update-atrPro", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi khi gửi yêu cầu');
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Sản phẩm đã được cập nhật thành công.',
                });
                window.location.href = "/admin/products/list-product";
            })
            .catch(error => {
                alert('Sai');
            });
    });
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
</body>
@endsection
