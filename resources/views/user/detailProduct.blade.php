@extends("user.index")

@section("content")

<style>
.container-detail-web {
    background: white;
    padding: 16px 0;
    border-radius: 8px;
    max-width: 1000px;
    margin: 0px auto;
}
</style>

<div class="container-detail-web">
    <div class="container-product">
        <div>
            <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}"
                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
            <div class="action-container d-flex">
                <!-- Nút yêu thích -->
                <div class="like-container mr-2">
                    <form action="{{route('user.product.love', ['id' => $product->product_id])}}" method="POST">
                        @csrf
                        <button type="submit" class="love-icon-detail">
                            <i class="fa-solid fa-heart"></i> Yêu thích
                        </button>
                    </form>
                </div>
                <div class="view-container">
                    <button type="submit" class="view-icon-detail">
                        <i class="fa-solid fa-eye"></i>{{$viewCount}} Lượt xem
                    </button>
                </div>
            </div>
        </div>

        <div class="contai">
            <div class="options">
                <h1 class="text-title-prodict-list">{{ $product->name }}</h1>
                <p class="text-muted">{{ $product->subtitle }}</p>
                <!-- <div class="product-meta">
                    <span class="rating">★★★★☆</span>
                    <span class="comments">| 120 Đánh giá</span>
                    <span class="sales">| 500 đã bán</span>
                </div> -->
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
                <div class="product-stock">
                    Còn lại: <span id="product-stock">{{ $product->attributeProducts->first()->in_stock }}</span>
                </div>
                <p id="error-message" style="color: red; display: none;">Số lượng không thể vượt quá số lượng có
                    sẵn!</p>
            </div>

            <div class="d-flex">
                @if ($product->is_active)
                    <!-- Sản phẩm đang hoạt động -->
                    <button type="button" class="custom-btn" onclick="addToCart()">Thêm vào giỏ hàng</button>

                    <form id="add-to-cart-form" action="{{ route('user.cart.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="color_id" id="selected-color" value="">
                        <input type="hidden" name="size_id" id="selected-size" value="">
                        <input type="hidden" name="qty" id="qty-hidden" min="1" value="1">
                        <button type="submit" class="custom-buy">Mua ngay</button>
                    </form>
                @else
                    <!-- Sản phẩm không hoạt động -->
                    <button type="button" class="custom-btn" disabled>Hết hàng</button>
                @endif
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

    <!-- accordion -->
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div id="reviews" class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseOne">
                    Chi tiết sản phẩm
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="button-header mb-3 ml-2">
                        <button>
                            Chi tiết sản phẩm: <strong>{{ $product->name }}</strong> <i class="fa fa-star"></i>
                        </button>
                    </div>
                    <div class="detail-content ">
                        <div class="detail-section">
                            <h3>Danh Mục:</h3>
                            <p> {{ $product->category->name ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="detail-section">
                            <h3>Màu sắc:</h3>
                            <p>
                                @php
                                $colors =
                                $product->attributeProducts->unique('color_id')->pluck('color.name')->toArray();
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
                                $description = nl2br(e($description));
                                $description = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $description);
                                $description = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $description);
                                @endphp

                                {!! $description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseTwo">
                    Đánh giá
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    @if($reviews->isEmpty())
                    <div class="text-center">
                        <p>Chưa có đánh giá nào về sản phẩm này.</p>
                    </div>
                    @else
                    <div id="reviewsContainer" class="reviewsContainer">
                        @foreach ($reviews as $review)
                        <p>Đánh giá của bạn:</p>
                        <div class="review-admin-user">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span
                                        class="review-date">{{ optional($review->created_at)->format('d-m-Y') ?? 'N/A' }}</span>
                                    <span
                                        class="review-time">{{ optional($review->created_at)->format('H:i') ?? 'N/A' }}</span>
                                </div>
                                <div class="rating text-right">
                                    <!-- PHP/Blade Logic -->
                                    @for ($i = 1; $i <= 5; $i++) @if ($review->rating >= $i)
                                        ★
                                        @else
                                        ☆
                                        @endif
                                        @endfor
                                        <span class="rating-text">
                                            @switch($review->rating)
                                            @case(1)
                                            Tệ
                                            @break
                                            @case(2)
                                            Không hài lòng
                                            @break
                                            @case(3)
                                            Bình thường
                                            @break
                                            @case(4)
                                            Hài lòng
                                            @break
                                            @case(5)
                                            Tuyệt vời
                                            @break
                                            @default
                                            Chưa đánh giá
                                            @endswitch
                                        </span>
                                </div>
                            </div>
                            <p class="review-text">
                                {{!empty($review->comment) ? $review->comment : 'Bạn không Đánh giá gì về sản phẩm'}}
                            </p>
                            <img src="{{ asset('storage/' . $review->image) }}" class="image-review-product">
                            @if($review->replies->isNotEmpty())
                            @foreach($review->replies as $reply)
                            <div class="admin-response">
                                <div class="admin-info mr-2">
                                    <p><strong>Phản hồi của người bán</strong></p>
                                </div>
                                <p>{{ $reply->content }}</p>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($hasPurchased && $hasReviewed && !$reviewsExist)
                    <!-- Form đánh giá -->
              
                 <form action="{{ route('user.product.addReview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="review-form">
                            <p>Đánh giá của bạn:</p>
                            <div class="form-start-detail-select">
                                <label class="text-muted mr-3">Chất lượng sản phẩm</label>
                                <div class="stars">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" data-text="Tuyệt vời">★</label>

                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" data-text="Hài lòng">★</label>

                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" data-text="Bình thường">★</label>

                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" data-text="Không hài lòng">★</label>

                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" data-text="Tệ">★</label>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <div class="comment-section">
                                <textarea name="comment" class="customReviewTest" id="reviewText"
                                    placeholder="Đánh giá... (Tùy chọn)"></textarea>
                            </div>
                        </div>
                        <div class="comment-upload-section">
                            <label class="upload-button">
                                <i class="fa-solid fa-upload"></i>
                                <input type="file" name="image" />
                            </label>
                            <button class="btn btn-primary" type="submit">Đánh giá</button>
                        </div>
                    </form>
              

                    @elseif(!$hasReviewed)
                    <p class="text-center">Bạn đã đánh giá sản phẩm này rồi.</p>
                    @else
                    <p class="text-center">Hãy mua hàng và cho chúng tôi đánh giá để cải thiện dịch vụ nha!!!</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseThree">
                    Tất Cả Đánh Giá
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="button-header ml-2">
                        <button>
                            Đánh giá sản phẩm: <strong>{{ $product->name }}</strong> <i class="fa fa-star"></i>
                        </button>
                    </div>
                </div>
                @php
                $totalReviews = $reviewAll->count();
                $totalStars = $reviewAll->sum('rating');
                $averageRating = $totalReviews > 0 ? round($totalStars / $totalReviews, 1) : 0;

                // Tạo chuỗi sao
                $stars = str_repeat('★', floor($averageRating));
                if ($averageRating - floor($averageRating) >= 0.5) {
                $stars .= '☆';
                }
                @endphp
                <div class="header-reting-and-filer align-items-center">
                    <div class="rating-summary">
                        <h2>{{ $averageRating }} trên 5</h2>
                        <div class="stars">{{ $stars }}</div>
                    </div>

                    <div class="review-filter">
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id])}}">
                                <div class="total-reviews">
                                    <p> Tất cả đánh Giá</p>
                                </div>
                            </a>
                        </button>
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 5])}}">
                                <div class="one">
                                    <p>5 sao</p>
                                </div>
                            </a>
                        </button>
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 4])}}">
                                <div class="one">
                                    <p>4 sao</p>
                                </div>
                            </a>
                        </button>
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 3])}}">
                                <div class="one">
                                    <p>3 sao</p>
                                </div>
                            </a>
                        </button>
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 2])}}">
                                <div class="one">
                                    <p>2 sao</p>
                                </div>
                            </a>
                        </button>
                        <button>
                            <a href="{{route('user.product.detail', ['id' => $product->product_id, 'rating' => 1])}}">
                                <div class="one">
                                    <p>1 sao</p>
                                </div>
                            </a>
                        </button>
                    </div>
                </div>

                <!-- Reviews -->
                @foreach ($reviewAll as $value)
                <div class="review ">

                    <h5>{{$value->user->name}}</h5>
                    <div class="text-start">
                        <div class="rating-all-reviews">
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
                        <div class="review-header">
                            <span
                                class="review-date">{{ optional($value->created_at)->format('d-m-Y H:i') ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <p class="review-text"> {{ $value->comment }}</p>
                    <div class="review-images">
                        <img src="{{ asset('storage/' . $value->image) }}" class="image-review-product">
                    </div>
                    @if($value->replies->isNotEmpty())
                    @foreach($value->replies as $reply)
                    <div class="admin-response">
                        <div class="admin-info mr-2">
                            <p><strong>Phản hồi của người bán</strong></p>
                        </div>
                        <p>{{ $reply->content }}</p>
                    </div>
                    @endforeach
                    @endif
                    <div class="action-buttons">
                        <form action="{{ route('user.product.like', $value->review_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="custom-btn-active-admin status-btn-active">
                                <i class="fas fa-thumbs-up "></i>{{$value->likes->count()}}
                            </button>
                        </form>
                        <form action="{{ route('user.product.report', $value->review_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button><i class="fas fa-flag"></i> {{$value->reports->count()}}</button>
                        </form>
                    </div>
                </div>
                @endforeach
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
                <div class="product-container">
                    <form action="{{route('user.product.love', ['id' => $relatedProduct->product_id])}}" method="POST">
                        @csrf
                        <button type="submit" class="cart-icon love-icon">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </form>
                    <div class="product-item">
                        <a href="{{ route('user.product.detail', $relatedProduct->product_id) }}"
                            class="product-card-link">
                            <div class="card">
                                <img src="{{ asset('storage/' . $relatedProduct->main_image_url) }}"
                                    alt="{{ $relatedProduct->name }}" class="product-image"
                                    onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                                <div class="card-body">
                                    @php
                                    // Lấy danh sách giá từ các thuộc tính
                                    $prices = $relatedProduct->attributeProducts->pluck('price');
                                    $originalMinPrice = $prices->min() ?? 0;
                                    $originalMaxPrice = $prices->max() ?? 0;

                                    // Khởi tạo giá hiển thị
                                    $minPrice = $originalMinPrice;
                                    $maxPrice = $originalMaxPrice;

                                    // Kiểm tra xem sản phẩm có giảm giá không
                                    $promotion = $relatedProduct->promPerProducts
                                    ->sortByDesc('created_at') // Sắp xếp giảm dần theo ngày tạo
                                    ->first()?->promPer; // Lấy khuyến mãi mới nhất

                                    if ($promotion) {
                                    if ($promotion->discount_amount) {
                                    $minPrice = max(0, $originalMinPrice - $promotion->discount_amount);
                                    $maxPrice = max(0, $originalMaxPrice - $promotion->discount_amount);
                                    } elseif ($promotion->discount_percentage) {
                                    $minPrice = max(0, $originalMinPrice * (1 - $promotion->discount_percentage / 100));
                                    $maxPrice = max(0, $originalMaxPrice * (1 - $promotion->discount_percentage / 100));
                                    }
                                    }
                                    @endphp
                                    <span class="product-price">
                                        @if ($promotion)
                                        <!-- Hiển thị giá khuyến mãi -->
                                        @if ($minPrice === $maxPrice)
                                        <!-- Giá trị khuyến mãi chỉ hiển thị một giá -->
                                        <strong>{{ number_format($originalMinPrice, 0, ',', '.') }}</strong> ₫<br>
                                        <strong>{{ number_format($minPrice, 0, ',', '.') }}</strong> ₫
                                        @else
                                        <!-- Hiển thị giá gốc và giá khuyến mãi (phạm vi) -->
                                        <strong>{{ number_format($originalMinPrice, 0, ',', '.') }} -
                                            {{ number_format($originalMaxPrice, 0, ',', '.') }}</strong> ₫<br>
                                        <strong>{{ number_format($minPrice, 0, ',', '.') }} -
                                            {{ number_format($maxPrice, 0, ',', '.') }}</strong> ₫
                                        @endif
                                        @else
                                        <!-- Không có khuyến mãi -->
                                        @if ($originalMinPrice === $originalMaxPrice)
                                        <!-- Hiển thị một giá duy nhất -->
                                        {{ number_format($originalMinPrice, 0, ',', '.') }} ₫
                                        @else
                                        <!-- Hiển thị phạm vi giá -->
                                        {{ number_format($originalMinPrice, 0, ',', '.') }} -
                                        {{ number_format($originalMaxPrice, 0, ',', '.') }} ₫
                                        @endif
                                        @endif
                                    </span>
                                    <h5 class="classname-special-title">{{ $relatedProduct->name }}</h5>
                                    <p class="classname-special-subtitle">{{ $relatedProduct->subtitle }}</p>
                                    @php
                                    $createdAt = \Carbon\Carbon::parse($relatedProduct->created_at);
                                    $now = \Carbon\Carbon::now();
                                    $isNewProduct = $createdAt->diffInDays($now) <= 7; @endphp @if ($isNewProduct) <p
                                        class="classname-special-button-new">Mới</p>
                                        @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>



</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash === '#reviews') {
            const reviewsSection = document.querySelector('#reviews');
            if (reviewsSection) {
                reviewsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
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

            // Hiển thị thông báo lỗi bằng SweetAlert2
            Swal.fire({
                title: 'Thông báo',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
    });
    var productAttributes = {!! $productAttributesJson !!};

function checkStockAvailability() {
    const color = document.getElementById('selected-color').value;
    const size = document.getElementById('selected-size').value;

    if (!color || !size) {
        document.getElementById('product-stock').innerText = '... sản phẩm';
        document.getElementById('product-price').innerText = '0 VND';
        return;
    }

    // Lấy thông tin tồn kho và giá dựa trên lựa chọn
    let selectedStock = 0;
    let selectedPrice = 0;

    productAttributes.forEach(attribute => {
        if (attribute.color_id == color && attribute.size_id == size) {
            selectedStock = attribute.in_stock;
            selectedPrice = attribute.price; // Lấy giá từ productAttributes
        }
    });

    // Cập nhật tồn kho và giá
    if (selectedStock > 0) {
        document.getElementById('product-stock').innerText = `${selectedStock} sản phẩm có sẵn`;
        document.getElementById('product-price').innerText = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(selectedPrice);
    } else {
        document.getElementById('product-stock').innerText = 'Hết hàng';
        document.getElementById('product-price').innerText = '0 VND';
    }
}

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

    // Kiểm tra lại số lượng tồn kho khi thay đổi màu sắc
    checkStockAvailability();
}

// Hàm chọn kích thước
function selectSize(sizeId, element) {
    document.getElementById('selected-size').value = sizeId;

    // Cập nhật giao diện nút kích thước
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.classList.remove('active');
    });
    element.classList.add('active');

    // Kiểm tra lại thông tin tồn kho và giá
    checkStockAvailability();
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
            Swal.fire({
                icon: 'error',
                title: 'Vượt quá kho!',
                text: 'Số lượng yêu cầu vượt quá số lượng sản phẩm có sẵn trong kho!',
                confirmButtonText: 'OK',
                timer: 5000,
                willClose: () => {
                    location.reload();
                }
            });
            return;
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
            Swal.fire({
                icon: 'error',
                title: 'Vượt quá kho!',
                text: 'Số lượng yêu cầu vượt quá số lượng sản phẩm có sẵn trong kho!',
                confirmButtonText: 'OK',
                timer: 5000,
                willClose: () => {
                    location.reload();
                }
            });
            return; // Không cho phép thay đổi nếu vượt quá kho
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
            Swal.fire({
                title: 'Thông báo',
                text: 'Vui lòng chọn đầy đủ màu sắc, kích thước và số lượng.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
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
                // Kiểm tra nếu có dữ liệu thành công
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thêm vào giỏ hàng thành công!',
                        html: 'Sản phẩm của bạn đã được thêm vào giỏ hàng.<br>Bạn có thể tiếp tục mua sắm hoặc kiểm tra giỏ hàng.',
                        showConfirmButton: true,
                        confirmButtonText: 'Ok',
                    }).then(() => {
                        location.reload();  // Làm mới trang nếu thêm vào giỏ thành công
                    });
                }
                // Kiểm tra nếu có thông báo lỗi
                else if (data.error) {
                    Swal.fire({
                        title: 'Thông báo',
                        text: data.error,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra, vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Lỗi kết nối',
                    text: 'Lỗi kết nối, vui lòng thử lại.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
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

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        if (!document.querySelector('input[name="rating"]:checked')) {
            e.preventDefault();
            Swal.fire({
                title: 'Thông báo',
                text: 'Vui lòng chọn sao trước khi gửi',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });
    const stars = document.querySelectorAll('.stars input[type="radio"]');
    const description = document.getElementById('rating-description');

    stars.forEach(star => {
        star.addEventListener('change', () => {
            const label = document.querySelector(`label[for="star${star.value}"]`);
            description.textContent = label.getAttribute('data-text');
        });
    });
    </script>

@endsection
