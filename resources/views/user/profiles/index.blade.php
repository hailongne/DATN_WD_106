@extends("user.index")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accountAndOrder/profile.css') }}">
@endpush

@section("content")
<div class="profile-container">
    <div class="profile-sidebar">
        <h3>Trang tài khoản</h3>
        <p>Xin chào, <strong>{{ $user->name }}</strong></p> <!-- Hiển thị tên người dùng -->
        <div class="custom-menu-profile">
            <a href="{{route('user.profiles.showUserInfo')}}" class="custom-text-link-profile">Thông tin tài khoản</a>
            <br />
            <a href="{{ route('user.profiles.edit-profile', ['id' => Auth::user()->user_id]) }}" class="custom-text-link-profile">Chỉnh sửa tài khoản</a>
            <br />
            <a href="{{route('user.product.listLove', ['id' => Auth::user()->user_id])}}" class="nav-link">
                        <!-- <i class="fa-solid fa-heart"></i> -->
                        Sản phẩm yêu thích
                        </a>
                        <br/>
            <a href="{{route('user.order.history')}}" class="custom-text-link-profile">Lịch sử đơn hàng</a>
            <br />
        </div>
        <br />
        <br />
        <a href="{{route('home')}}" class="btn-logout"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
            xuất</a>
    </div>

    <!-- Bên phải -->
    <div class="profile-main">
        <h3>Tài khoản</h3>
        <div class="profile-info">
            <i class="fas fa-user"></i> Tên tài khoản: <strong>{{ $user->name }}</strong> <!-- Hiển thị tên -->
        </div>
        <div class="profile-info">
            <i class="fas fa-map-marker-alt"></i> Địa chỉ: {{ $user->address ?? 'Chưa cập nhật' }} <!-- Hiển thị địa chỉ -->
        </div>
        <div class="profile-info">
            <i class="fas fa-phone"></i> Điện thoại: {{ $user->phone ?? 'Chưa cập nhật' }} <!-- Hiển thị điện thoại -->
        </div>
    </div>
</div>
@endsection
