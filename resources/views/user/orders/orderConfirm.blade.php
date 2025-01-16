@extends('user.index')

<link rel="stylesheet" href="{{ asset('css/order-user/orderConfirm.css') }}">

@section('content')
<div class="container">
<!-- Thông Tin Người Nhận -->
<div class="container-form-order-confirm">
    <!-- Phần điền thông tin -->
    <div class="custom-header-order-confirm">
        <div class="logo"><img src="{{ asset('imagePro/image/logo/logo-remove2.png') }}" class="logo-gentlemanor">Gentle
            Manor - Shop thời trang nam <i class="fa fa-star"></i></div>
    </div>
    <section class="info-form-order-confirm">
        <p>Thông Tin Giao Hàng</p>
        <div class="user-info">
            <div class="avatar">
                <img src="{{asset('imagePro/icon/icon-avata.png')}}" alt="Avatar của người dùng"
                    onerror="this.src='default-avatar.png'">
            </div>
            <div class="user-details">
                <p class="user-name">{{ old('name', $user->name ?? '') }}</p>
                <p class="user-email">{{ old('name', $user->email ?? '') }}</p>
            </div>
        </div>
        <form id="orderForm" action="{{ route('user.order.checkoutcod') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="recipient_name" id="recipient_name" placeholder="Tên người nhận"
                    class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại nhận hàng"
                    value="{{ old('phone', $user->phone ?? '') }}" required>
            </div>
            <div class="mb-3">
                <input name="shipping_address" id="shipping_address" class="form-control" required
                    placeholder="Địa chỉ nhận hàng" value="{{ old('shipping_address', $user->address ?? '') }}"></input>
            </div>
            <!-- <input type="hidden" name="discount_code" id="hiddenDiscountCode" value=""> -->

            <!-- Nút xác nhận -->
            <div class="text-center mt-4 d-flex ">
                <input type="hidden" name="amount" id="hiddenTotalAmount" value="{{ $total }}">
                <a href="{{ route('user.cart.index') }}" class="custom-text-back-home"> <i class="fa fa-arrow-left"></i> Quay lại giỏ hàng</a>
                <div>
                    <button type="submit" class="custom-btn-order-cod">Thanh toán COD</button>
                    <button name="redirect" type="button" id="btnVnPay" class="custom-btn-order-vnpay">
                        Thanh Toán VNPay
                    </button>
                </div>
            </div>
        </form>

    </section>

    <!-- Phần danh sách sản phẩm -->
    <section class="products-detail-order-confirm">
        <div class="cart-items-container">
            @foreach ($productDetails as $product)
            <div class="product-card">
                <div class="product-image-order">
                    <!-- Hình ảnh sản phẩm -->
                    <img src="/storage/{{ $product['img'] }}" alt="{{ $product['name'] }}" class="product-image-detail"
                        onclick="openImageModal('/storage/{{ $product['img'] }}')"
                        onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                    <!-- Hiển thị số lượng trên ảnh -->
                    <div class="product-quantity-circle">{{ $product['quantity'] }}</div>
                </div>
                <div class="product-details">
                    <!-- Tên sản phẩm -->
                    <p class="product-name-order">{{ $product['name'] }}</p>
                    <!-- Màu và kích thước -->
                    <span class="product-attribute-order">Size:{{ $product['size'] }}</span>
                    <span class="product-attribute-order">Màu:{{ $product['color'] }}</span>
                </div>
                <div class="product-price-order">
                    <!-- Giá sản phẩm -->
                    {{ number_format($product['price'] * $product['quantity'], 0, ',', '.') }}₫
                </div>
            </div>
            @endforeach
        </div>
        <div class="total-order-confirm mt-3">

            <div class="order-item">
                <span class="order-label">Tạm tính:</span>
                <span class="order-value">
                {{ number_format($totalWithoutShipping, 0, ',', '.') }} đ</span>
            </div>
            
            <div class="order-item">
                <input type="text" name="discount_code" id="discountCode" placeholder="Nhập mã giảm giá" class="form-control">
                <button type="button" id="applyDiscount" class="custom-btn-apply-order">Áp dụng</button>
            </div>
            <div class="order-item">
                <span class="order-label">Giảm giá: </span>
                <span class="order-value" id="discountAmount">0 đ</span>
            </div>
<<<<<<< HEAD
            
            <div class="order-item">
                <span class="order-label">Phí vận chuyển:</span>
                <span class="order-value">40.000 đ</span>
            </div>
=======

>>>>>>> 4b289bba82cb21842d6b4a27f0f006f9c7bc13b9
            <hr class="order-divider">
            <div class="order-item total">
                <span class="order-label">Tổng cộng:</span>
                <span class="order-value" id="total">{{ number_format($total, 0, ',', '.') }} đ</span>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>



<!-- Form ẩn cho VNPay -->
<form id="vnpayForm" action="{{ route('checkout.vnpay') }}" method="POST" style="display: none;">
    @csrf
   <input type="hidden" name="amount" id="vnpayAmount" value="{{ $total }}">
    <input type="hidden" name="recipient_name" id="vnpayName" value="{{ old('name', $user->name ?? '') }}">
    <input type="hidden" name="phone" id="vnpayPhone" value="{{ old('phone', $user->phone ?? '') }}">
    <input type="hidden" name="shipping_address" id="vnpayAddress"
        value="{{ old('shipping_address', $user->address ?? '') }}">
    <input type="hidden" name="redirect" value="1">
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Hàm để cập nhật giá trị tổng tiền vào input hidden và nút COD
        function updateTotalAmount(newTotal) {
            var total = newTotal;
            // var total = $('#total').text().replace(' đ', '').replace(',', '') * 1000; // Lấy giá trị tổng từ #total
            $('#hiddenTotalAmount').val(total); // Gán giá trị vào input hidden
            $('#order-cod').text('Thanh toán COD (' + formatTotalAmount(total) + '   đ)'); // Cập nhật giá trị cho nút thanh toán COD
            $('#vnpayAmount').val(total); // Cập nhật giá trị vào form ẩn VNPay
        }

        // Hàm để format lại giá trị tổng tiền theo định dạng số tiền
        function formatTotalAmount(amount) {
            // Đảm bảo amount là số
            if (isNaN(amount)) amount = 0;

            // Định dạng số tiền theo kiểu Việt Nam
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            }).format(amount);
        }

        // Gọi hàm updateTotalAmount khi tải trang hoặc khi mã giảm giá được áp dụng
        updateTotalAmount({{ $total }});

        // Áp dụng mã giảm giá
            $('#applyDiscount').click(function () {
            const discountCode = $('#discountCode').val(); // Lấy mã giảm giá người dùng nhập
            const subtotal = {{ $totalWithoutShipping }}; // Tổng tiền ban đầu từ phía server

            $.ajax({
                url: '{{ route("user.order.applyDiscount") }}', // URL xử lý áp mã
                type: 'POST',
                data: {
                    discount_code: discountCode,
                    amount: subtotal,
                    _token: '{{ csrf_token() }}'
                },
             
                success: function (response) {
                    console.log(response); // Kiểm tra dữ liệu trả về từ server

                    if (response.success) {
                        // Hiển thị thông báo thành công bằng SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                        });

                        // Cập nhật số tiền giảm giá trên giao diện
                        $('#discountAmount').text('-' + formatTotalAmount(response.discount)); // Hiển thị số tiền giảm giá
                        $('#total').text(formatTotalAmount(response.newTotal)); // Cập nhật tổng tiền sau giảm giá
                        updateTotalAmount(response.newTotal);
                    } else {
                        // Hiển thị thông báo lỗi
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại',
                            text: response.message,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra, vui lòng thử lại.',
                    });
                }
            });
        });
    });


function openImageModal(imageSrc) {
    // Cập nhật ảnh trong modal
    document.getElementById('modalImage').src = imageSrc;

    // Mở modal
    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}
</script>

<script>
document.getElementById('btnVnPay').addEventListener('click', function () {
    // Lấy dữ liệu từ form chính
    const recipientName = document.getElementById('recipient_name').value;
    const phone = document.getElementById('phone').value;
    const shippingAddress = document.getElementById('shipping_address').value;

    // Gán dữ liệu vào form VNPay
    document.getElementById('vnpayName').value = recipientName;
    document.getElementById('vnpayPhone').value = phone;
    document.getElementById('vnpayAddress').value = shippingAddress;

    // Kiểm tra xem dữ liệu đã được gán chính xác chưa
    console.log({
        recipientName,
        phone,
        shippingAddress
    });

    // Gửi form VNPay
    document.getElementById('vnpayForm').submit();
});
</script>
@endsection
