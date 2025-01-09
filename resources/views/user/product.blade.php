@extends('user.index')

<link href="{{ asset('css/productList.css') }}" rel="stylesheet" type="text/css">

@section('content')
<div class="container">
    @if(session('alert'))
    <div class="alert alert-info" id="alert-message">
        {{ session('alert') }}
    </div>
    @endif
    <div class="product-list">
        <div class="filter-container d-flex justify-content-between align-items-center">
            <div class="filter-product-new">
                <a href="#" class="custom-btn-product-new">Mới nhất</a>
            </div>
            <div class="filter-product-new">
                <a href="#" class="custom-btn-product-new">Bán chạy</a>
            </div>
            <div class="filter-product-new">
                <a href="#" class="custom-btn-product-new">Hot</a>
            </div>
            <div class="filter-product-new">
                <a href="#" class="custom-btn-product-new">Áo</a>
            </div>
            <div class="filter-product-new">
                <a href="#" class="custom-btn-product-new">Quần</a>
            </div>
            <div class="filter-group">
                <select class="form-select filter-select" id="filterCategory">
                    <option value="">Giá</option>
                    <option value="high">Giá cao đến thấp</option>
                    <option value="low">Giá thấp đến cao</option>
                </select>
            </div>
        </div>
        <div class="product-items">
            @if(isset($listProduct) && $listProduct->isNotEmpty())
            @foreach($listProduct as $product)
            <div class="product-container">
                <form action="{{route('user.product.love', ['id' => $product->product_id])}}" method="POST">
                    @csrf
                    <button type="submit" class="cart-icon love-icon">
                        <i class="fa-solid fa-heart"></i>
                    </button>
                </form>
                <a href="{{ route('user.product.detail', $product->product_id) }}" class="cart-icon detail-icon">
                    <i class="fa fa-info-circle"></i>
                </a>
                <div class="product-item">
                    <a href="{{ route('user.product.detail', $product->product_id) }}" class="product-card-link">
                        <div class="card">
                            <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}"
                                class="product-image"
                                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                            <div class="card-body">
                                <h5 class="classname-special-title">{{ $product->name }}</h5>
                                <p class="classname-special-subtitle">{{ $product->subtitle }}</p>
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
                                    <strong>{{ number_format($originalMinPrice, 0, ',', '.') }}</strong> đ<br>
                                    <strong>{{ number_format($minPrice, 0, ',', '.') }}</strong> đ
                                    @else
                                    <!-- Hiển thị giá gốc và giá khuyến mãi (phạm vi) -->
                                    <strong>{{ number_format($originalMinPrice, 0, ',', '.') }} -
                                        {{ number_format($originalMaxPrice, 0, ',', '.') }}</strong> đ<br>
                                    <strong>{{ number_format($minPrice, 0, ',', '.') }} -
                                        {{ number_format($maxPrice, 0, ',', '.') }}</strong> đ
                                    @endif
                                    @else
                                    <!-- Không có khuyến mãi -->
                                    @if ($originalMinPrice === $originalMaxPrice)
                                    <!-- Hiển thị một giá duy nhất -->
                                    {{ number_format($originalMinPrice, 0, ',', '.') }} đ
                                    @else
                                    <!-- Hiển thị phạm vi giá -->
                                    {{ number_format($originalMinPrice, 0, ',', '.') }} -
                                    {{ number_format($originalMaxPrice, 0, ',', '.') }} đ
                                    @endif
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            @elseif(isset($products) && $products->isNotEmpty())
            @foreach($listProduct as $product)
            <div class="product-container">
                <form action="{{route('user.product.love', ['id' => $product->product_id])}}" method="POST">
                    @csrf
                    <button type="submit" class="cart-icon love-icon">
                        <i class="fa-solid fa-heart"></i>
                    </button>
                </form>
                <a href="{{ route('user.product.detail', $product->product_id) }}" class="cart-icon detail-icon">
                    <i class="fa fa-info-circle"></i>
                </a>
                <div class="product-item">
                    <a href="{{ route('user.product.detail', $product->product_id) }}" class="product-card-link">
                        <div class="card">
                            <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}"
                                class="product-image"
                                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                            <div class="card-body">
                                <h5 class="classname-special-title">{{ $product->name }}</h5>
                                <p class="classname-special-subtitle">{{ $product->subtitle }}</p>
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
                                ->sortByDesc('created_at') // Sort discounts by the latest creation date
                                ->first()?->promPer; // Get the first discount (latest one)

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
                                    <!-- Hiển thị giá gốc (gạch ngang) và giá khuyến mãi -->
                                    <strong>{{ number_format($originalMinPrice, 0, ',', '.') }} -
                                        {{ number_format($originalMaxPrice, 0, ',', '.') }}</strong> đ
                                    <br>
                                    <strong>{{ number_format($minPrice, 0, ',', '.') }} -
                                        {{ number_format($maxPrice, 0, ',', '.') }}</strong> đ
                                    @else
                                    <!-- Hiển thị giá gốc nếu không có khuyến mãi -->
                                    {{ number_format($originalMinPrice, 0, ',', '.') }} -
                                    {{ number_format($originalMaxPrice, 0, ',', '.') }} đ
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            @else
            <!-- Nếu cả listProduct và products đều không có sản phẩm -->
            <div class="center-container">
                <img src="{{asset('imagePro/icon/icon-no-search-product.png')}}" alt="No results found"
                    class="center-img">
                <h5>Không tìm thấy kết quả nào</h5>
                <p>Hãy sử dụng các từ khóa chung hơn</p>
            </div>
            @endif
        </div>
    </div>
    @endsection