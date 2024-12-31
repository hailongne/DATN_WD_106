<link href="{{ asset('css/header.css') }}" rel="stylesheet">

<div class="container-fluid border-bottom">
    <div class="row d-flex justify-content-between align-items-center">
        <!-- Cột bên trái - Logo -->
        <div class="col-2 d-flex align-items-center justify-content-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('imagePro/image/logo/logo-remove.png') }}" alt="Gentle Manor" style="width: 80px;">
            </a>
        </div>

        <!-- Cột giữa - Tìm kiếm và danh mục -->
        <div class="col-7" style="padding-left: 15px; padding-right: 15px;">
            <div class="row">
                <!-- Hàng ngang 1 - Thanh tìm kiếm -->
                <div class="col-12 mb-1 pt-4 form-search">
                <form class="d-flex justify-content-center" action="{{ route('user.product.search') }}" method="GET">
                <input type="search" name="search" placeholder="Tìm kiếm sản phẩm" class="form-control search-bar" aria-label="Search" value="{{ request()->input('search') }}">
                <button type="submit" class="d-none">Tìm kiếm</button> <!-- Optional: to show the button -->
            </form>
                </div>
            </div>

            <div class="row">
                <!-- Hàng ngang 2 - Danh mục -->
                <div class="col">
                    <div class="header-nav align-items-center mt-2">
                        <ul class="d-flex">
                            <li class="dropdown-item">
                                <a href="{{ route('user.product.list') }}">Sản Phẩm</a>
                            </li>

                            @foreach ($categories as $category)
                                <li class="dropdown-item">
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
                    <nav class="header-nav mt-3">
                        <a href="{{route('user.profiles.showUserInfo')}}" class="nav-link">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                        @if(Auth::check())
                        <a href="{{route('user.product.listLove', ['id' => Auth::user()->user_id])}}" class="nav-link">
                        <i class="fa-solid fa-heart"></i>
                        </a>
                        @endif
                        <div class="dropdown">
                            <a href="{{route('user.profiles.showUserInfo')}}" class="nav-link custom-Navlink">
                                <i class="fas fa-user"></i>
                                @if(Auth::check())
                                {{ Auth::user()->name }}
                                @else
                                Tài Khoản
                                @endif
                            </a>
                            <div class="dropdown-menu text-center">
                                @if(Auth::check())
                                <a href="{{route('user.profiles.showUserInfo')}}" class="dropdown-item">Thông tin chung</a>

                                <!-- Kiểm tra nếu người dùng có role 'admin' -->
                                @if(in_array(Auth::user()->role, [1, 3]))
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Trang quản trị</a>
                                @endif

                                <a href="{{route('user.order.history')}}" class="dropdown-item">Lịch sử mua hàng</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a href="{{route('home')}}"class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
                                    xuất</a>
                                @else
                                <a href="/login" class="dropdown-item">Đăng nhập</a>
                                <a href="/register" class="dropdown-item">Đăng ký</a>
                                @endif
                            </div>
                        </div>

                        <a href="javascript:void(0);" class="nav-link ml-3" onclick="toggleCartPopup()">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="custom-cart-count" id="cart-count">{{ $cartCount }}</span> <!-- Hiển thị số lượng giỏ hàng -->
                        </a>

                    </nav>
                </div>
            </div>
            @include('user.components.cart-popup', ['cartItems' => $cartItems ?? [], 'total' => $total ?? 0])
            <div class="row mt-3 mb-3 ml-3">
                <!-- Địa chỉ kéo dài -->
                <div class="col-12 text-start custom-text mb-2">
                    <a href="https://www.google.com/maps/search/13+P.+Trịnh+Văn+Bô,+Xuân+Phương,+Nam+Từ+Liêm,+Hà+Nội"
                        target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-map-marker-alt"></i> Địa chỉ: 13 Trịnh Văn Bô
                    </a>
                </div>
                <!-- Liên hệ và Hotline trong một hàng ngang -->
                <div class="col text-start custom-text d-flex">
                    <a href="https://www.facebook.com/Ngheflorist"
                        target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i> Liên hệ: fanpage
                    </a>
                    <a class="custom-text ms-3"><i class="fas fa-phone-alt"></i> Hotline: 0369312858</a>
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
        fetch('{{ route('user.cart.getCartCount') }}') // Gọi route lấy số lượng sản phẩm trong giỏ hàng
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

</script>
