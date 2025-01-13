<style>
    #carouselExample .carousel-item {
        transition: transform 1s ease-in-out !important;
    }

    .carousel-inner {
        overflow: hidden;
    }
</style>

@foreach($banners as $banner)
    <div class="banner">
        <a href="{{ $banner->link }}">
            <img src="{{ Storage::url('banners/' . $banner->image_url) }}" alt="Banner">
        </a>
    </div>
@endforeach



<div class="info-section">
    <div class="info-item">
        <div class="info-icon">
            <i class="fas fa-shipping-fast"></i> <!-- Miễn phí vận chuyển -->
        </div>
        <div class="info-text">
            <h4>Phí vận chuyển</h4>
            <p> Chỉ 40k toàn quốc</p>
        </div>
    </div>
    <div class="info-item">
        <div class="info-icon">
            <i class="fas fa-sync-alt"></i> <!-- Đổi hàng tận nhà -->
        </div>
        <div class="info-text">
            <h4>Đổi hàng tận nhà</h4>
            <p>Trong vòng 15 ngày</p>
        </div>
    </div>
    <div class="info-item">
        <div class="info-icon">
            <i class="fas fa-money-check-alt"></i> <!-- Thanh toán COD -->
        </div>
        <div class="info-text">
            <h4>Thanh toán COD</h4>
            <p>Yên tâm mua sắm</p>
        </div>
    </div>
    <div class="info-item">
        <div class="info-icon">
            <i class="fas fa-phone-alt"></i> <!-- Hotline -->
        </div>
        <div class="info-text">
            <h4>Hotline: 0369 312 858</h4>
            <p>Hỗ trợ bạn từ 8h30-24h00</p>
        </div>
    </div>
</div>
