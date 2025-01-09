<div class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('imagePro/image/logo/logo-admin.png') }}" alt="Gentle Manor" style="width: 100%;">
        </a>
    </div>

    <!-- Menu -->
    <ul class="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-chart-line"></i>Thống kê
            </a>
        </li>
        <li class="dropdown">
            <a href="#" class="toggle-link dropdown-toggle">
                <i class="fa-sharp-duotone fa-solid fa-list-check"></i> Quản lý
            </a>
            <ul id="managementSubmenu" class="submenu">
                <li><a href="{{ route('admin.products.index') }}"><i class="fa-solid fa-shirt"></i>Sản phẩm</a></li>
                <li><a href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-layer-group"></i> Danh mục</a></li>
                <li><a href="{{ route('admin.sizes.index') }}"><i class="fa-solid fa-maximize"></i> Size</a></li>
                <li><a href="{{ route('admin.colors.index') }}"><i class="fa-solid fa-droplet"></i> Màu</a></li>
                <li><a href="{{ route('admin.brands.index') }}"><i class="fa-solid fa-copyright"></i> Thương hiệu</a></li>
                <li><a href="{{ route('admin.coupons.index') }}"><i class="fa-solid fa-money-bill"></i> Mã giảm giá </a></li>
                <li><a href="{{ route('admin.inventories.index') }}"><i class="fa-solid fa-money-bill"></i>  Tồn kho </a></li>

            </ul>
        </li>
        <li>
            <a href="{{ route('admin.orders') }}">
                <i class="fa-solid fa-box"></i> Đơn hàng
            </a>
        </li>
        <li>
            <a href="{{route('admin.reviews.index')}}">
                <i class="fa-solid fa-comment"></i> Bình luận
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.listUser') }}">
                <i class="fa-solid fa-user"></i> Tài khoản
            </a>
        </li>
    </ul>
</div>
