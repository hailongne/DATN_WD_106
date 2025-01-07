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
        <li>
            <a href="{{ route('home') }}">
            <i class="fa-solid fa-house"></i> Trang chủ
            </a>
        </li>
    </ul>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Account Dropdown
    const accountToggle = document.querySelector('.nav-link.dropdown-toggle');
    const accountDropdown = document.querySelector('.dropdown-menu');

    accountToggle.addEventListener('click', function(e) {
        e.preventDefault();
        accountDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!accountDropdown.contains(e.target) && !accountToggle.contains(e.target)) {
            accountDropdown.classList.remove('show');
        }
    });

    // Management Dropdown
    const managementToggle = document.querySelector('.toggle-link.dropdown-toggle');
    const managementDropdown = document.querySelector('.submenu');

    managementToggle.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Dropdown Mã giảm giá được click!');
        managementDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!managementDropdown.contains(e.target) && !managementToggle.contains(e.target)) {
            managementDropdown.classList.remove('show');
        }
    });

    // Coupon Dropdown
    const couponToggle = document.querySelector('.toggle-link-coupon.dropdown-toggle');
    const couponDropdown = document.querySelector('.submenu-coupon');

    couponToggle.addEventListener('click', function(e) {
        e.preventDefault();
        couponDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!couponDropdown.contains(e.target) && !couponToggle.contains(e.target)) {
            couponDropdown.classList.remove('show');
        }
    });

    // Coupon Dropdown
    const commentToggle = document.querySelector('.toggle-link-comment.dropdown-toggle');
    const commentDropdown = document.querySelector('.submenu-comment');
    commentToggle.addEventListener('click', function(e) {
        e.preventDefault();
        commentDropdown.classList.toggle('show');
    });
    document.addEventListener('click', function(e) {
        if (!commentDropdown.contains(e.target) && !commentToggle.contains(e.target)) {
            commentDropdown.classList.remove('show');
        }
    });

    // Highlight active menu item
    const currentURL = window.location.href;
    const menuItems = document.querySelectorAll(".submenu li a, .submenu-coupon li a, .submenu-comment li a");

    menuItems.forEach(item => {
        if (item.href === currentURL) {
            const parentDropdown = item.closest('.dropdown, .dropdown-coupon, .dropdown-comment');
            if (parentDropdown) {
                parentDropdown.classList.add('active');
            }
        }
    });
});
</script>
