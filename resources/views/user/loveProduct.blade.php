@extends('user.index')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush
@section('content')
<div class="container">
    @if(session('alert'))
    <div class="alert alert-info" id="alert-message">
        {{ session('alert') }}
    </div>
    @endif
    <div class="product-list">
        <div class="filter-container">
            <h2 class="text-title-prodict-list text-start">Sản phẩm yêu thích</h2>
            <div class="filter-group">
                <label class="mr-2 text-align-center"> Lọc theo danh mục: </label>
                <select class="form-select filter-select mr-4" id="filterCategory">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $category)
                    <option value="$category->category_id">{{ $category->name }}</option>
                    @endforeach
                </select>
                <label class="mr-2 text-align-center"> Sắp xếp: </label>
                <select class="form-select filter-select" id="filterCategory">
                    <option value="">Mới Nhất</option>
                    <option value="">Giá: Tăng dần</option>
                    <option value="">Giá: Giảm dần</option>
                    <option value="">Sản phẩm nổi bật</option>
                    <option value="">Sản phẩm nổi bật</option>
                    <option value="">Tên: A-Z</option>
                    <option value="">Tên: Z-A</option>
                </select>
            </div>
        </div>
        <hr class="line-filter-product" />
        <div class="row">
            <div class="product-items">
                @if($listProduct->sortByDesc('created_at')->isEmpty())
                <p class="no-product-message">Không tìm thấy sản phẩm trong danh sách.</p>
                @else
                <div class="product-slide">
                    @foreach($listProduct as $product)
                    <div class="product-container">
                        <form action="{{route('user.product.love', ['id' => $product->product_id])}}" method="POST">
                            @csrf
                            <button type="submit" class="cart-icon love-icon">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </form>
                        <div class="product-item">
                            <a href="{{ route('user.product.detail', $product->product_id) }}"
                                class="product-card-link">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $product->main_image_url) }}"
                                        alt="{{ $product->name }}" class="product-image"
                                        onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                                    <div class="card-body">
                                        @php
                                        // Lấy danh sách giá từ các thuộc tính
                                        $prices = $product->attributeProducts->pluck('price');
                                        $originalMinPrice = $prices->min() ?? 0;
                                        $originalMaxPrice = $prices->max() ?? 0;

                                        // Khởi tạo giá hiển thị
                                        $minPrice = $originalMinPrice;
                                        $maxPrice = $originalMaxPrice;

                                        // Kiểm tra xem sản phẩm có giảm giá không
                                        $promotion = $product->promPerProducts
                                        ->sortByDesc('created_at') // Sắp xếp giảm dần theo ngày tạo
                                        ->first()?->promPer; // Lấy khuyến mãi mới nhất

                                        if ($promotion) {
                                        if ($promotion->discount_amount) {
                                        $minPrice = max(0, $originalMinPrice - $promotion->discount_amount);
                                        $maxPrice = max(0, $originalMaxPrice - $promotion->discount_amount);
                                        } elseif ($promotion->discount_percentage) {
                                        $minPrice = max(0, $originalMinPrice * (1 - $promotion->discount_percentage /
                                        100));
                                        $maxPrice = max(0, $originalMaxPrice * (1 - $promotion->discount_percentage /
                                        100));
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
                                        <h5 class="classname-special-title">{{ $product->name }}</h5>
                                        <p class="classname-special-subtitle">{{ $product->subtitle }}</p>
                                        @php
                                        $createdAt = \Carbon\Carbon::parse($product->created_at);
                                        $now = \Carbon\Carbon::now();
                                        $isNewProduct = $createdAt->diffInDays($now) <= 7; @endphp @if ($isNewProduct)
                                            <p class="classname-special-button-new">Mới</p>
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
</div>
@push('scripts')
<script src="{{asset('script/chat.js')}}"></script>
@endpush
@endsection