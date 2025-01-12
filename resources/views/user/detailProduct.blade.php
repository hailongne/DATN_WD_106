@extends("user.index")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/detailProduct.css') }}">
@endpush

@section("content")

<div class="body">
    <div class="container-product">
        <div>
            <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}"
                class="product-image-detail"
                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
        </div>

        <div class="contai">
            <div class="options">
                <h1 class="product-name">{{ $product->name }}</h1>
                <p class="text-muted">{{ $product->subtitle }}</p>
                <div class="product-meta">
                    <span class="rating">★★★★☆</span>
                    <span class="comments">| 120 bình luận</span>
                    <span class="sales">| 500 đã bán</span>
                </div>
                <p class="price" id="product-price">
                    @php
                    $prices = $product->attributeProducts->pluck('price');
                    $minPrice = $prices->min();
                    $maxPrice = $prices->max();
                    $defaultPrice = $product->attributeProducts->first()->price ?? 0;
                    @endphp
                    @if ($minPrice == $maxPrice)
                    <span data-min-price="{{ $minPrice }}" data-max-price="{{ $maxPrice }}">
                        {{ number_format($minPrice, 0, ',', '.') }} ₫
                    </span>
                    @else
                    <span data-min-price="{{ $minPrice }}" data-max-price="{{ $maxPrice }}">
                        {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }} ₫
                    </span>
                    @endif
                </p>
                <p>Màu sắc: </p>
                <div class="color-options">
                    @foreach($product->attributeProducts->unique('color_id') as $attributeProduct)
                    <div class="color-option" style="background-color: {{ $attributeProduct->color->color_code }};"
                        onclick="changeColor('{{ $attributeProduct->color->color_id }}', this)">
                    </div>
                    @endforeach
                </div>
                <p class="section-title">Size:</p>
                <div class="size-options">
                    @foreach($product->attributeProducts->unique('size_id') as $attributeProduct)
                    <button class="size-option" data-id="{{ $attributeProduct->size->size_id }}"
                        data-price="{{ $attributeProduct->price }}" data-stock="{{ $attributeProduct->in_stock }}"
                        onclick="selectSize('{{ $attributeProduct->size->size_id }}', this)">
                        {{ $attributeProduct->size->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="quantity-container d-flex">
                <label for="quantity" class="form-label mr-2">Số lượng: </label>
                <div class="custom-quantity" onclick="changeQuantity(-1)">-</div>
                <input type="number" name="display-qty" id="quantity" class="custom-quantity-input" min="1" value="1"
                    onchange="updateQuantity(this.value)">
                <div class="custom-quantity" onclick="changeQuantity(1)">+</div>
                <p class="product-stock">
                    Còn lại: <span id="product-stock">{{ $product->attributeProducts->first()->in_stock }}</span>
                </p>
                <p id="error-message" style="color: red; display: none;">Số lượng không thể vượt quá số lượng có
                    sẵn!</p>
            </div>

            <div class="d-flex">
                <button type="button" class="custom-btn" onclick="addToCart()">Thêm vào giỏ hàng</button>

                <form id="add-to-cart-form" action="{{ route('user.cart.buy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="color_id" id="selected-color" value="">
                    <input type="hidden" name="size_id" id="selected-size" value="">
                    <input type="hidden" name="qty" id="qty-hidden" min="1" value="1">
                    <button type="submit" class="custom-buy">Mua ngay</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast thông báo -->
    <div id="toast-notification" class="toast align-items-center text-white bg-danger border-0 position-fixed p-2"
        style="top: 20px; right: 20px; display: none; z-index: 1055;" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toast-message">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
                onclick="closeToast()"></button>
        </div>
    </div>

    <!-- Chi tiết sản phẩm -->
    <div class="container-details">
        <div class="button-header mb-3">
            <button>
                Chi tiết sản phẩm <i class="fa fa-star"></i>
            </button>
        </div>
        <div class="detail-content">

            <div class="detail-section">
                <h3>Danh Mục:</h3>
                <p> {{ $product->category->name ?? 'Chưa cập nhật' }}</p>
            </div>
            <div class="detail-section">
                <h3>Màu sắc:</h3>
                <p>
                    @php
                    $colors = $product->attributeProducts->unique('color_id')->pluck('color.name')->toArray();
                    @endphp
                    {{ implode(', ', $colors) }}
                </p>
            </div>

            <div class="detail-section mb-2">
                <h3>Size:</h3>
                <p>
                    @php
                    $sizes = $product->attributeProducts->unique('size_id')->pluck('size.name')->toArray();
                    @endphp
                    {{ implode(', ', $sizes) }}
                </p>
            </div>
            <div class="product-description">
                <div class="description-header">
                    <h3>Mô tả sản phẩm:</h3>
                </div>
                <div class="description-content-detail">
                    @php
                    $description = $product->description
                    ? $product->description
                    : 'Mô tả sản phẩm đang được cập nhật...';

                    // Xử lý Markdown thủ công
                    $description = nl2br(e($description)); // Chuyển đổi xuống dòng
                    $description = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $description); // In đậm
                    $description = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $description); // In nghiêng
                    @endphp

                    {!! $description !!}
                </div>
            </div>
        </div>
    </div>

    <div class="container-related">
        <div class="button-header">
            <button> 
                Sản phẩm liên quan <i class="fa fa-star"></i>
            </button>
        </div>
        <div class="product-carousel">
            @if($relatedProducts->isEmpty())
            <p class="no-product-message">Không tìm thấy sản phẩm liên quan.</p>
            @else
            <div class="product-slide">
                @foreach($relatedProducts as $relatedProduct)
                <div class="product-item">
                    <a href="{{ route('user.product.detail', $relatedProduct->product_id) }}" class="product-card-link">
                        <div class="card">
                            <img src="{{ asset('storage/' . $relatedProduct->main_image_url) }}"
                                alt="{{ $relatedProduct->name }}" class="product-image"
                                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                            <div class="card-body">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                @php
                                $prices = $relatedProduct->attributeProducts->pluck('price');
                                $minPrice = $prices->min();
                                $maxPrice = $prices->max();
                                @endphp
                                <div class="product-info">
                                    <span class="product-price">
                                        @if ($minPrice == $maxPrice)
                                        {{ number_format($minPrice, 0, ',', '.') }} VND
                                        @else
                                        {{ number_format($minPrice, 0, ',', '.') }} -
                                        {{ number_format($maxPrice, 0, ',', '.') }} VND
                                        @endif
                                    </span>
                                    <a href="{{ route('user.product.detail', $relatedProduct->product_id) }}"
                                        class="cart-icon">
                                        <i class="fa fa-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @if($hasPurchased)
    <div class="container-review">
        <h2>Các bạn hãy đánh giá sản phẩm nha!</h2>
        @if($reviews->isEmpty())
        <div class="text-center">
            <h5>Chưa có bình luận nào cho sản phẩm này.</h5>
            <p>Hãy là người bình luận đầu tiên</p>
        </div>
        @else
        <div id="reviewsContainer" class="reviewsContainer">
            @foreach ($reviews as $review)
            <div class="review">
                <span class="review-date">
                    {{ optional($review->created_at)->format('d-m-Y') ?? 'N/A' }}
                </span>
                <span class="review-time">
                    {{ optional($review->created_at)->format('H:i') ?? 'N/A' }}
                </span>
                <div class="rating text-right">
                    @if ($review->rating == 1)
                    ★
                    @elseif ($review->rating == 2)
                    ★★
                    @elseif ($review->rating == 3)
                    ★★★
                    @elseif ($review->rating == 4)
                    ★★★★
                    @elseif ($review->rating == 5)
                    ★★★★★
                    @endif
                </div>
                <p class="review-text">{{ $review->comment }}</p>
            </div>
            <!-- Tin nhắn trả lời của admin -->
            @if($review->replies->isNotEmpty())
            @foreach($review->replies as $reply)
            <div class="admin-response">

                <div class="admin-info mr-2">
                    <p>Quản trị viên: </p>
                </div>
                <p>
                    {{ $reply->content }}
                </p>
            </div>
            @endforeach
            @endif
            @endforeach
        </div>
        @endif
        <form action="{{ route('user.product.addReview') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="review-form">
                <p>Đánh giá của bạn:</p>
                <!-- <div class="stars">
                    <input type="radio" id="star1" name="rating" value="1" onclick="selectStar(1)">
                    <label for="star1">★</label>

                    <input type="radio" id="star2" name="rating" value="2" onclick="selectStar(2)">
                    <label for="star2">★★</label>

                    <input type="radio" id="star3" name="rating" value="3" onclick="selectStar(3)">
                    <label for="star3">★★★</label>

                    <input type="radio" id="star4" name="rating" value="4" onclick="selectStar(4)">
                    <label for="star4">★★★★</label>

                    <input type="radio" id="star5" name="rating" value="5" onclick="selectStar(5)">
                    <label for="star5">★★★★★</label>
                </div> -->
                <div class="stars">
    <input type="radio" id="star5" name="rating" value="5">
    <label for="star5">★</label>
    <input type="radio" id="star4" name="rating" value="4">
    <label for="star4">★</label>
    <input type="radio" id="star3" name="rating" value="3">
    <label for="star3">★</label>
    <input type="radio" id="star2" name="rating" value="2">
    <label for="star2">★</label>
    <input type="radio" id="star1" name="rating" value="1">
    <label for="star1">★</label>
</div>
                <input type="hidden" name="product_id" value="{{$product->product_id}}" id="">
                <div class="comment-section">
                    <textarea name="comment" class="customReviewTest" id="reviewText"
                        placeholder="Bình luận..."></textarea>
                    <button class="btn" type="submit">
                        Bình luận
                    </button>
                </div>
            </div>
            <label class="upload-button">
                <i class="fa-solid fa-plus"></i>
                <!-- Dấu cộng -->
                <input type="file" name="image" width="100px" height="100px" />
            </label>
            <button class="btn" type="submit">
                Submit Review
            </button>
        </form>
    </div>
    @endif
    <div class="container-review">
        <h2>ĐÁNH GIÁ SẢN PHẨM</h2>
        <div class="details">
            <div class="rating-info">
                <h2>4.9</h2>
                <div class="stars">★★★★★</div>
            </div>
            <a href="{{route('user.product.detail', ['id' => $product->product_id])}}">
                <div class="total-reviews">
                    <p> Tất cả đánh Giá</p>
                </div>
            </a>
            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 5])}}">
                <div class="one">
                    <p>5 Sao (8)</p>
                </div>
            </a>
            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 4])}}">
                <div class="one">
                    <p>4 Sao (2)</p>
                </div>
            </a>
            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 3])}}">
                <div class="one">
                    <p>3 Sao (0)</p>
                </div>
            </a>
            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 2])}}">
                <div class="one">
                    <p>2 Sao (0)</p>
                </div>
            </a>
            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 1])}}">
                <div class="one">
                    <p>1 Sao (0)</p>
                </div>
            </a>
        </div>
        <div id="reviewsContainer">
            @foreach ($reviewAll as $value)
            <div class="review1">
                <div class="review-header">
                    <div class="user-info">
                        <img src="https://via.placeholder.com/40" alt="User Avatar" />
                        <h3>{{$value->user->name}}</h3>
                    </div>
                </div>
                <span class="review-date">{{ optional($value->created_at)->format('d-m-Y H:i') ?? 'N/A' }}</span>
                <div class="rating">
                    @if ($value->rating == 1)
                    ★
                    @elseif ($value->rating == 2)
                    ★★
                    @elseif ($value->rating == 3)
                    ★★★
                    @elseif ($value->rating == 4)
                    ★★★★
                    @elseif ($value->rating == 5)
                    ★★★★★
                    @endif
                </div>
                <p class="review-text"> {{ $value->comment }}</p>
                <div class="review-images">
                    <div class="image-container">
                        <img src="{{Storage::url($value->image)}}" alt="Review Image" />
                        <div class="action-buttons">
                            <form action="{{ route('user.product.like', $value->review_id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="custom-btn-active-admin status-btn-active">
                                    <i class="fas fa-thumbs-up ">{{$value->likes->count()}}</i>
                                </button>
                            </form>
                            <form action="{{ route('user.product.report', $value->review_id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="custom-btn-active-admin status-btn-active">
                                    <i class="fas fa-flag">{{$value->reports->count()}}</i>
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var reviewsContainer = document.getElementById('reviewsContainer');
        reviewsContainer.scrollTop = reviewsContainer.scrollHeight;
    });

    let selectedColor = '';
    let selectedSize = '';
    let inStock = parseInt(document.getElementById('product-stock').innerText) || 0;

    //Mua ngay
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        const color = document.getElementById('selected-color').value;
        const size = document.getElementById('selected-size').value;
        const qty = document.getElementById('qty-hidden').value;

        // Kiểm tra các lựa chọn
        if (!color || !size || !qty || qty < 1) {
            e.preventDefault(); // Ngừng gửi form nếu thiếu thông tin

            let message = '';
            if (!color) {
                message += 'Vui lòng chọn màu sắc.\n';
            }
            if (!size) {
                message += 'Vui lòng chọn kích thước.\n';
            }
            // Hiển thị thông báo lỗi
            alert(message);
            return;
        }
    });

    function changeColor(colorId, element) {
        // Cập nhật giá trị vào input ẩn
        document.getElementById('selected-color').value = colorId;

        // Xóa lớp active khỏi tất cả các nút màu
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.classList.remove('active');
        });

        // Thêm lớp active cho nút màu được chọn
        element.classList.add('active');
    }


    // Hàm size
    function selectSize(sizeId, element) {
        // Cập nhật giá trị vào input ẩn
        document.getElementById('selected-size').value = sizeId;

        // Lấy giá và tồn kho từ thuộc tính data của nút được chọn
        const newPrice = element.getAttribute('data-price');
        const newStock = element.getAttribute('data-stock');

        // Cập nhật giá sản phẩm hiển thị
        document.getElementById('product-price').innerText = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(newPrice);

        // Cập nhật số lượng tồn kho hiển thị
        document.getElementById('product-stock').innerText = `${newStock} sản phẩm`;

        // Cập nhật số lượng tồn kho vào biến toàn cục
        inStock = parseInt(newStock);

        // Xóa lớp active khỏi tất cả các nút kích thước
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            option.classList.remove('active');
        });

        // Thêm lớp active cho kích thước được chọn
        element.classList.add('active');
    }


    // Hàm cập nhật giá sản phẩm
    function updatePrice(price, type, element) {
        // Loại bỏ trạng thái "selected" của các lựa chọn hiện tại
        if (type === 'color') {
            document.querySelectorAll('.color-option').forEach(option => {
                option.classList.remove('selected');
            });
        } else if (type === 'size') {
            document.querySelectorAll('.size-option').forEach(option => {
                option.classList.remove('selected');
            });
        }

        // Đánh dấu lựa chọn hiện tại là "selected"
        element.classList.add('selected');

        // Cập nhật giá hiển thị
        const priceElement = document.getElementById('product-price');
        priceElement.textContent = `${Number(price).toLocaleString()} VND`;
    }

    function changeQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        let currentQuantity = parseInt(quantityInput.value) || 1;
        currentQuantity += change;

        if (currentQuantity < 1) currentQuantity = 1;
        // Kiểm tra nếu số lượng vượt quá số lượng có sẵn trong kho
        if (currentQuantity > inStock) {
            alert('Số lượng yêu cầu vượt quá số lượng sản phẩm có sẵn trong kho!');
            return; // Không cho phép thay đổi nếu vượt quá kho
        }

        quantityInput.value = currentQuantity;
        updateQuantity(currentQuantity);
    }

    function updateQuantity(value) {
        let qty = parseInt(value);

        if (isNaN(qty) || qty < 1) {
            qty = 1;
        }

        // Kiểm tra nếu số lượng vượt quá số lượng có sẵn trong kho
        if (qty > inStock) {
            alert('Số lượng yêu cầu vượt quá số lượng sản phẩm có sẵn trong kho!');
            return; // Dừng lại nếu vượt quá kho
        }

        // Cập nhật giá trị của input hiển thị
        document.getElementById('quantity').value = qty;

        // Cập nhật giá trị của hidden input
        document.getElementById('qty-hidden').value = qty;
    }

    function addToCart() {
        const color = document.getElementById('selected-color').value;
        const size = document.getElementById('selected-size').value;
        const qty = document.getElementById('qty-hidden').value;

        // Kiểm tra nếu chưa chọn màu sắc, kích thước hoặc số lượng
        if (!color || !size || !qty) {
            alert("Vui lòng chọn đầy đủ màu sắc, kích thước và số lượng.");
            return; // Dừng lại nếu thiếu lựa chọn
        }

        // Gửi yêu cầu AJAX đến server
        const formData = new FormData();
        formData.append('product_id', document.querySelector('[name="product_id"]').value);
        formData.append('color_id', color);
        formData.append('size_id', size);
        formData.append('qty', qty);
        formData.append('_token', document.querySelector('[name="_token"]').value);

        fetch("{{ route('user.cart.add') }}", {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng.');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(error => {
                alert('Lỗi kết nối, vui lòng thử lại.');
            });
    }

    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        toastMessage.innerText = message;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    function closeToast() {
        document.getElementById('toast-notification').style.display = 'none';
    }
    // xử lý phần đánh giá

    let selectedStars = 0;

    function selectStar(star) {
        const stars = document.querySelectorAll(".star");
        selectedStars = star;
        stars.forEach((s, index) => {
            s.classList.toggle("selected", index < star);
        });
    }

    function handleFiles(files) {
        const uploadedImagesDiv =
            document.getElementById("uploadedImages");
        uploadedImagesDiv.innerHTML = "";

        Array.from(files).forEach((file) => {
            const imgContainer = document.createElement("div");
            imgContainer.className = "image-container";

            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            img.alt = file.name;

            const removeButton = document.createElement("button");
            removeButton.className = "remove-image";
            removeButton.innerHTML = "&times;";
            removeButton.onclick = () => {
                imgContainer.remove();
            };

            imgContainer.appendChild(img);
            imgContainer.appendChild(removeButton);
            uploadedImagesDiv.appendChild(imgContainer);
        });
    }
    </script>
    @endsection