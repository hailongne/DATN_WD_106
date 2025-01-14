@extends('user.index')

<style>
    .order-success {
    text-align: center;
    padding: 50px 20px;
    background: linear-gradient(to bottom, #f3f3f3, #d9d9d9); /* Tông màu xám nhạt */
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    max-width: 600px;
    margin: 50px auto;
    font-family: 'Roboto', sans-serif;
}

.success-icon img {
    width: 150px;
    height: auto;
    margin-bottom: 20px;
}

.success-title {
    font-size: 2.2rem;
    color: #333; /* Màu xám đậm */
    font-weight: bold;
    margin-bottom: 15px;
}

.success-message {
    font-size: 1.1rem;
    color: #555; /* Màu xám trung bình */
    margin-bottom: 20px;
    line-height: 1.6;
}

.order-actions .btn-home {
    display: inline-block;
    background-color: #333; /* Màu xám đậm thay vì đen hoàn toàn */
    color: #fff; /* Màu trắng cho chữ */
    padding: 10px 20px;
    font-size: 1rem;
    border: 1px solid #444; /* Viền xám đậm */
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.order-actions .btn-home:hover {
    border: 1px solid #666; /* Viền xám trung bình khi hover */
    background-color: #555; /* Màu xám trung bình khi hover */
    transform: translateY(-2px);
}

.btn {
    background-color: black !important;
    color: white !important;
    padding: 10px 20px!important;
    font-size: 14px!important;
    text-transform: uppercase !important;
    text-decoration: none !important;
    border: 1px solid black !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
    color: white;
}

.btn:hover {
    background-color: white !important;
    color: black !important;
    outline: 2px solid rgba(0, 0, 0, 0.1) !important;
    outline-offset: -2px !important;
}
</style>
@section('content')

<div class="order-success">
    <div class="success-icon">
        <img src="{{ asset('imagePro/icon/icon-cart-success.png') }}" alt="Order Success Icon" />
    </div>
    <h1 class="success-title">Đặt hàng thành công!</h1>
    <p class="success-message">Xin chào, <strong>{{ $userName }}</strong>.</p>
    <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.</p>
    <div class="order-actions">
        <a href="{{route('user.order.history')}}" class="btn btn-primary btn-home">Lịch sử đơn hàng</a>
        <a href="{{ route('home') }}" class="btn btn-primary btn-home">Tiếp tục mua hàng</a>
    </div>
</div>
@endsection
<script>
    // Hiệu ứng khi tải trang
document.addEventListener("DOMContentLoaded", function () {
    const successDiv = document.querySelector(".order-success");
    successDiv.style.opacity = "0";
    successDiv.style.transform = "scale(0.9)";
    setTimeout(() => {
        successDiv.style.opacity = "1";
        successDiv.style.transform = "scale(1)";
        successDiv.style.transition = "all 0.5s ease";
    }, 100);
});
if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
