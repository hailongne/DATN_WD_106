@extends('user.index')
@if(session('error'))
<script>
alert("{{ session('error') }}");
</script>
@endif

@section('content')

<div class="container mb-5">

    <!-- Danh sách sản phẩm -->
    <div class="button-header mt-5">
        <button>
            Gentle Manor - Danh sách sản phẩm <i class="fa fa-star"></i>
        </button>
    </div>
    <div class="row">
        <div class="product-carousel">
            @if($listProduct->isEmpty())
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
            </div>
            @endif
        </div>
    </div>

    <!-- Sản phẩm bán chạy -->
    <div class="button-header mt-5">
        <button>
            Gentle Manor - Sản phẩm bán chạy <i class="fa fa-star"></i>
        </button>
    </div>
    <div class="row product-carousel">
        @if($bestSellers->isEmpty())
        <p class="no-product-message">Không tìm thấy sản phẩm bán chạy.</p>
        @else
        <div class="product-slide">
            @foreach($bestSellers as $soldProduct)
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
                    <a href="{{ route('user.product.detail', $soldProduct->product_id) }}" class="product-card-link">
                        <div class="card">
                            <img src="{{ asset('storage/' . $soldProduct->main_image_url) }}"
                                alt="{{ $soldProduct->name }}" class="product-image"
                                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                            <div class="card-body">
                                <h5 class="classname-special-title">{{ $soldProduct->name }}</h5>
                                <p class="classname-special-subtitle">{{ $soldProduct->subtitle }}</p>
                                @php
                                $prices = $soldProduct->attributeProducts->pluck('price');
                                $minPrice = $prices->min();
                                $maxPrice = $prices->max();
                                @endphp
                                <span class="product-price">
                                    @if ($minPrice == $maxPrice)
                                    {{ number_format($minPrice, 0, ',', '.') }} đ
                                    @else
                                    {{ number_format($minPrice, 0, ',', '.') }} -
                                    {{ number_format($maxPrice, 0, ',', '.') }} đ
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>


    <!-- Sản phẩm hot -->
    <div class="button-header mt-5">
        <button>
            Gentle Manor - Sản phẩm hot <i class="fa fa-star"></i>
        </button>
    </div>
    <div class="row product-carousel">
        @if($hotProducts->isEmpty())
        <p class="no-product-message">Không tìm thấy sản phẩm hot.</p>
        @else
        <div class="product-slide">
            @foreach($hotProducts as $hotProduct)
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
                    <a href="{{ route('user.product.detail', $hotProduct->product_id) }}" class="product-card-link">
                        <div class="card">
                            <img src="{{ asset('storage/' . $hotProduct->main_image_url) }}"
                                alt="{{ $hotProduct->name }}" class="product-image"
                                onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                            <div class="card-body">
                                <h5 class="classname-special-title">{{ $hotProduct->name }}</h5>
                                <p class="classname-special-subtitle">{{ $soldProduct->subtitle }}</p>
                                @php
                                $prices = $hotProduct->attributeProducts->pluck('price');
                                $minPrice = $prices->min();
                                $maxPrice = $prices->max();
                                @endphp
                                <span class="product-price">
                                    @if ($minPrice == $maxPrice)
                                    {{ number_format($minPrice, 0, ',', '.') }} đ
                                    @else
                                    {{ number_format($minPrice, 0, ',', '.') }} -
                                    {{ number_format($maxPrice, 0, ',', '.') }} đ
                                    @endif
                                </span>
                                <a href="{{ route('user.product.detail', $product->product_id) }}" class="cart-icon">
                                    <i class="fa fa-info-circle"></i>
                                </a>
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

@endsection