@extends("user.index")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/detailProduct.css') }}">
@endpush

@section("content")
<div class="body">
    <div class="container">
        <div class="container-product">
            <div>
                <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}"
                    class="product-image-detail"
                    onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
            </div>

            <div class="contai">
                <div class="options">
                    <h1 class="product-name">{{ $product->name }}</h1>
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
                            {{ number_format($minPrice, 0, ',', '.') }} VND
                        </span>
                        @else
                        <span data-min-price="{{ $minPrice }}" data-max-price="{{ $maxPrice }}">
                            {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                            VND
                        </span>
                        @endif
                    </p>
                    <p>Color: </p>
                    <div class="color-options">
                        @foreach($product->attributeProducts as $attributeProduct)
                        <button class="color-option" style="background-color: {{ $attributeProduct->color->name }};"
                            onclick="changeColor('{{ $attributeProduct->color->color_id }}', this)">
                        </button>
                        @endforeach
                    </div>
                    <p class="section-title">Size:</p>
                    <div class="size-options">
                        @foreach($product->attributeProducts->unique('size_id') as $attributeProduct)
                        <button class="size-option" data-id="{{ $attributeProduct->size->size_id }}"
                            data-price="{{ $attributeProduct->price }}"
                            onclick="selectSize('{{ $attributeProduct->size->size_id }}', this)">
                            {{ $attributeProduct->size->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="quantity-container d-flex">
                    <label for="quantity" class="form-label">Số lượng: </label>
                    <div class="btn btn-info" onclick="changeQuantity(-1)">-</div>
                    <input type="number" name="display-qty" id="quantity" class="form-control" min="1" value="1"
                        onchange="updateQuantity(this.value)">
                    <div class="btn btn-info" onclick="changeQuantity(1)">+</div>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn custom-cart mr-2" onclick="addToCart()">Thêm vào giỏ hàng</button>
                    <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="color_id" id="selected-color" value="">
                        <input type="hidden" name="size_id" id="selected-size" value="">
                        <input type="hidden" name="qty" id="qty-hidden" value="1">
                        <button type="submit" class="btn custom-buy">Mua ngay</button>
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

        <div class="container-details">
            <div class="button-header mb-3">
                <button>
                    Chi tiết sản phẩm <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="detail-content">

                <div class="detail-section">
                    <h3>Danh Mục:</h3>
                    <p>{{ $product->category->name ?? 'Chưa cập nhật' }}</p>
                </div>

                <div class="detail-section">
                    <h3>Kho:</h3>
                    <p>{{ $product->stock ?? 'Không xác định' }}</p>
                </div>

                <div class="detail-section">
                    <h3>Thương hiệu:</h3>
                    @foreach($product->attributeProducts as $attributeProduct)
                    <p>{{ $attributeProduct->brand->name ?? 'Chưa cập nhật' }}</p>
                    @endforeach
                </div>

                <div class="detail-section">
                    <h3>Màu sắc:</h3>
                    <p>
                        @foreach($product->attributeProducts as $attributeProduct)
                        <span
                            style="color: {{ $attributeProduct->color->name }}; padding: 2px 6px; margin-right: 5px; border-radius: 4px;">
                            {{ $attributeProduct->color->name }}
                        </span>
                        @endforeach
                    </p>
                </div>

                <div class="detail-section mb-5">
                    <h3>Size:</h3>
                    <p>
                        @foreach($product->attributeProducts->unique('size_id') as $attributeProduct)
                        <span
                            style="padding: 2px 6px; margin-right: 5px; border: 1px solid #333; border-radius: 100px;">
                            {{ $attributeProduct->size->name }}
                        </span>
                        @endforeach
                    </p>
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
                        <a href="{{ route('product.detail', $relatedProduct->product_id) }}" class="product-card-link">
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
                                        <a href="{{ route('product.detail', $relatedProduct->product_id) }}"
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


        <div class="container-review">
            <!-- Phần danh sách đánh giá -->
            <div class="button-header">
                <button>
                    Đánh giá sản phẩm <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="reviews">
                <div class="review">
                    <h3>anhan224</h3>
                    <div class="review-header">
                        <div class="rating">★★★★☆</div>
                        <span class="review-date">2021-10-29 10:05</span>
                    </div>
                    <p class="review-text">
                        Hàng đẹp, sản phẩm rất đáng mua nhé
                        Mua hàng ở làn bên shop nên rất yên tâm về chất lượng ❤️❤️
                        Sẽ còn mua hàng shop dài dài hihi! 😊
                    </p>
                    <div class="review-images">
                        <img src="https://picsum.photos" alt="Review Image 1"
                            onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';" />
                        <img src="https://picsum.photos" alt="Review Image 2"
                            onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';" />
                        <img src="https://picsum.photos" alt="Review Image 3"
                            onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';" />
                    </div>
                    <div class="review-buttons">
                        <button class="like-button" onclick="toggleLike(this)">
                            <i class="fa-regular fa-thumbs-up"></i>
                        </button>
                        <span class="like-count">0</span>
                        <button class="report-button" onclick="toggleReport(this)">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </button>
                        <span class="report-count">0</span>
                    </div>
                </div>
                <!-- Phần gửi đánh giá -->
                <div class="review-form">
                    <h2>Viết đánh giá</h2>
                    <div class="stars">
                        <span class="star" onclick="selectStar(1)">★</span>
                        <span class="star" onclick="selectStar(2)">★</span>
                        <span class="star" onclick="selectStar(3)">★</span>
                        <span class="star" onclick="selectStar(4)">★</span>
                        <span class="star" onclick="selectStar(5)">★</span>
                    </div>
                    <textarea placeholder="Write your review here..."></textarea>
                    <button class="btn">Submit Review</button>
                </div>
            </div>
        </div>

        <script>
        let selectedColor = '';
        let selectedSize = '';
        document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
            const color = document.getElementById('selected-color').value;
            const size = document.getElementById('selected-size').value;

            if (!color || !size) {
                e.preventDefault(); // Ngăn form gửi đi
                showToast('Vui lòng chọn màu sắc và kích thước.', true);
            }
        });

        document.querySelector('.custom-cart').addEventListener('click', function(e) {
            const color = document.getElementById('selected-color').value;
            const size = document.getElementById('selected-size').value;

            if (!color || !size) {
                e.preventDefault(); // Ngăn gửi form nếu chưa chọn thuộc tính
                showToast('Vui lòng chọn màu sắc và kích thước.', true);
            } else {
                showToast('Thêm vào giỏ hàng thành công!', false);
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

            // Cập nhật giá hiển thị
            const newPrice = element.getAttribute('data-price');
            document.getElementById('product-price').innerText = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(newPrice);

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

            if (currentQuantity < 1) currentQuantity = 1; // Không cho phép giá trị nhỏ hơn 1
            quantityInput.value = currentQuantity;
            updateQuantity(currentQuantity); // Cập nhật giá trị vào input ẩn
        }

        function updateQuantity(value) {
            const qtyInput = document.getElementById('qty-hidden');
            qtyInput.value = value;
        }

        function showToast(message, isError = true) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');

            // Cập nhật thông điệp
            toastMessage.innerText = message;

            // Hiện toast
            toast.style.display = 'block';

            // Thêm màu tùy theo trạng thái lỗi/success
            if (isError) {
                toast.classList.remove('bg-success');
                toast.classList.add('bg-danger');
            } else {
                toast.classList.remove('bg-danger');
                toast.classList.add('bg-success');
            }

            // Ẩn toast sau 3 giây
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function closeToast() {
            const toast = document.getElementById('toast-notification');
            toast.style.display = 'none';
        }


        function closeToast() {
            const toast = document.getElementById('toast-notification');
            toast.style.display = 'none';
        }
        </script>


        @endsection
