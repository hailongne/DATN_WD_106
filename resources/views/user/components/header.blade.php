<link href="{{ asset('css/header.css') }}" rel="stylesheet">

<div class="container-fluid border-bottom">
    <div class="row d-flex justify-content-between align-items-center">
        <!-- Cột bên trái - Logo -->
        <div class="col-1 d-flex align-items-center justify-content-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('imagePro/image/logo/logo-remove.png') }}" alt="Gentle Manor"
                    style="width: 100px; margin-left:25px">
            </a>
        </div>

        <!-- Cột giữa - Tìm kiếm và danh mục -->
        <div class="col-8" style="padding-left: 15px; padding-right: 15px; width: 90%">
            <div class="row">
                <!-- Hàng ngang 1 - Thanh tìm kiếm -->
                <div class="col-12 pt-4 form-search">
                    <form class="d-flex justify-content-center" action="{{ route('user.product.search') }}"
                        method="GET">
                        <input type="search" name="search" placeholder="Tìm kiếm sản phẩm"
                            class="form-control search-bar" aria-label="Search"
                            value="{{ request()->input('search') }}">
                        <button type="submit" class="d-none">Tìm kiếm</button> <!-- Optional: to show the button -->
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- Hàng ngang 2 - Danh mục -->
                <div class="col">
                    <div class="header-nav align-items-center">
                        <ul class="d-flex">
                            <li class="dropdown-item-menu">
                                <a href="{{ route('user.product.list') }}">Sản Phẩm</a>
                            </li>

                            @foreach ($categories as $category)
                                <li class="dropdown-item-menu">
                                    <a href="{{ route('user.product.list', $category->category_id) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột bên phải - Icon và địa chỉ -->
        <div class="col-3">
            <div class="row d-flex">
                <!-- Hàng ngang 1 - Các icon và liên kết -->
                <div class="col">
                    <nav class="header-nav mt-4">
                        <a href="{{route('user.profiles.showUserInfo')}}" data-bs-toggle="tooltip" title="Trang chủ"
                            class="nav-link">
                            <i class="fas fa-home"></i>
                        </a>

                        @if(Auth::check())
                        <a href="{{route('user.product.listLove', ['id' => Auth::user()->user_id])}}"
                            data-bs-toggle="tooltip" title="Sản phẩm yêu thích" class="nav-link">
                            <i class="fa-solid fa-heart"></i>
                        </a>

                        @if(in_array(Auth::user()->role, [1,3]))
                        <a href="{{ route('admin.dashboard') }}" data-bs-toggle="tooltip" title="Hệ thống quản lý"
                            class="nav-link"><i class="fas fa-user-shield mr-1"></i></a>
                        @endif
                        @endif

                        <div class="dropdown">
                            <a href="{{route('user.profiles.showUserInfo')}}" class="nav-link custom-Navlink">
                                <i class="fas fa-user mr-1"></i>
                                @if(Auth::check())
                                {{ Auth::user()->name }}
                                @else
                                Tài Khoản
                                @endif
                            </a>
                            <div class="dropdown-menu">
                                @if(Auth::check())
                                <a href="{{route('user.profiles.showUserInfo')}}" class="dropdown-item">Tài khoản của
                                    tôi</a>


                                <!-- <a href="{{route('user.product.listLove', ['id' => Auth::user()->user_id])}}"
                                    class="dropdown-item">
                                    Sản phẩm yêu thích
                                </a> -->
                                <a href="{{ route('user.coupons.list') }}" class="dropdown-item">
                                    Mã giảm giá của tôi
                                </a>
                                <a href="{{route('user.order.history')}}" class="dropdown-item">Đơn mua</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a href="{{route('home')}}" class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
                                    xuất</a>
                                @else
                                <a href="/login" class="dropdown-item">Đăng nhập</a>
                                <a href="/register" class="dropdown-item">Đăng ký</a>
                                @endif
                            </div>
                        </div>
                        @if(Auth::check())
                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="Giỏ hàng" class="nav-link ml-3"
                            onclick="toggleCartPopup()">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="custom-cart-count" id="cart-count">{{ $cartCount }}</span>
                            <!-- Hiển thị số lượng giỏ hàng -->
                        </a>
                        @else
                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="Giỏ hàng" class="nav-link ml-3"
                            onclick="toggleCartPopup()" style="pointer-events: none;  opacity: 0.7;">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="custom-cart-count" id="cart-count">0</span>
                            <!-- Hiển thị số lượng giỏ hàng -->
                        </a>
                        @endif
                    </nav>
                </div>
            </div>
            @include('user.components.cart-popup', ['cartItems' => $cartItems ?? [], 'total' => $total ?? 0])
            <div class="row mt-3 mb-3 ml-3">
                <!-- Địa chỉ kéo dài -->
                <div class="text-start custom-text mb-2">
                    <a href="https://www.google.com/maps/search/13+P.+Trịnh+Văn+Bô,+Xuân+Phương,+Nam+Từ+Liêm,+Hà+Nội"
                        target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-map-marker-alt" style="font-size: 0.7rem;"></i> Địa chỉ: 13 Trịnh Văn Bô
                    </a>
                    <a class="custom-text ms-3"><i class="fas fa-phone-alt" style="font-size: 0.7rem;"></i> Hotline:
                        0369312858</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCartPopup() {
    const cartPopup = document.getElementById('cart-popup');
    cartPopup.classList.toggle('d-none');

    if (!cartPopup.classList.contains('d-none')) {
        fetchCartItems();
    }
}
// Hàm cập nhật số lượng giỏ hàng
function updateCartCount() {
    fetch('{{route('user.cart.getCartCount') }}')
        .then(response => response.json())
        .then(data => {
            // Cập nhật giá trị số lượng giỏ hàng vào phần tử có id "cart-count"
            document.getElementById('cart-count').textContent = data.cart_count;
        })
        .catch(error => {
            console.error('Error fetching cart count:', error);
        });
}
updateCartCount();

// Kích hoạt tooltips cho tất cả các phần tử có data-bs-toggle="tooltip"
document.addEventListener('DOMContentLoaded', function() {
    var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipElements.forEach(function(element) {
        new bootstrap.Tooltip(element, {
            offset: [0, 5],
            placement: 'bottom'
        });
    });
});
</script>
