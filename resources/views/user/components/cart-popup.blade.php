<!-- custom-cart-popup.blade.php -->
<div id="cart-popup" class="custom-cart-popup d-none">
    <div class="custom-cart-popup-overlay" onclick="toggleCartPopup()"></div>
    <div class="custom-cart-popup-content">
        <button class="custom-close-popup" onclick="toggleCartPopup()">&times;</button>
        <h4 class="custom-cart-title">Giỏ hàng của bạn</h4>

        <!-- Danh sách sản phẩm trong giỏ hàng -->
        <div class="custom-cart-items-container" id="cart-items-list">
            <p id="loading-text">Đang tải giỏ hàng...</p>
        </div>

        <div class="custom-cart-footer" id="cart-footer">
            <p class="custom-total-amount" id="total-amount">Tổng tiền: 0đ</p>
            <div class="custom-cart-actions">
                <a href="{{ route('user.cart.index') }}" class="custom-add-cart-popup">Xem giỏ hàng</a>
            </div>
        </div>
    </div>
</div>

<script>
 function fetchCartItems() {
    fetch('/cart/cart-popup')
        .then(response => response.json())
        .then(data => {
            const cartFooter = document.getElementById('cart-footer');
            const cartItemsList = document.getElementById('cart-items-list');
            const totalAmountElement = document.getElementById('total-amount');
            
            if (data.cartItems && data.cartItems.length > 0) {
                let cartItemsHtml = '';
                let totalAmount = 0;

                data.cartItems.forEach(item => {
                    const attributeProduct = item.product.attribute_products.find(attr =>
                        attr.size_id === item.size_id && attr.color_id === item.color_id);
                    const price = attributeProduct ? attributeProduct.price : 0;
                    const color = attributeProduct ? attributeProduct.color.name : 'Chưa có thông tin';
                    const size = attributeProduct ? attributeProduct.size.name : 'Chưa có thông tin';

                    cartItemsHtml += `
                        <div class="custom-product-card">
                            <div class="custom-product-image">
                                <a href="/product/${item.product.product_id}" class="custom-product-card-link">
                                    <img src="/storage/${item.product.main_image_url}" alt="${item.product.name}"
                                    class="product-image-detail"
                                    onerror="this.onerror=null; this.src='{{ asset('imagePro/image/no-image.png') }}';">
                                </a>
                            </div>
                            <div class="custom-product-details">
                                <h5 class="custom-product-name">${item.product.name}</h5>
                                <p class="custom-product-price">${price.toLocaleString()}đ</p>
                                <div class="custom-details-row">
                                    <p class="custom-product-attribute">Màu sắc: ${color}</p>
                                    <p class="custom-product-attribute">Size: ${size}</p>
                                </div>
                                <p class="custom-product-quantity">Số lượng: ${item.qty}</p>
                            </div>
                            <div class="custom-remove-btn">
                                <form action="{{ route('user.cart.remove', '') }}/${item.id}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="custom-btn-remove" onclick="confirm('Bạn có muốn xóa sản phẩm này hay không ?')">&times;</button>
                                </form>
                            </div>
                        </div>
                    `;

                    totalAmount += price * item.qty;
                });

                cartItemsList.innerHTML = cartItemsHtml;
                totalAmountElement.innerText = `Tổng tiền: ${totalAmount.toLocaleString()}đ`;
                cartFooter.style.display = 'block'; // Hiển thị phần footer khi có sản phẩm
            } else {
                cartItemsList.innerHTML = `
                <div class="cart-empty-gm">
                    <div class="cart-icon-gm">🛒</div>
                    <p class="cart-message-gm">Giỏ hàng của bạn đang trống.</p>
                </div>`;
                cartFooter.style.display = 'none'; // Ẩn phần footer khi giỏ hàng trống
            }

            document.getElementById('loading-text').style.display = 'none';
        })
        .catch(error => {
            console.error('Lỗi khi tải giỏ hàng:', error);
            document.getElementById('loading-text').innerText = 'Không thể tải giỏ hàng.';
        });
}

</script>