@extends('admin.index')

<link rel="stylesheet" href="{{asset('css/admin/coupon.css')}}">

@section('content')

<style>
/* .radio-group {
    display: none;
} */

.text-danger {
    font-size: 12px;
}
</style>

<body>
    <form action="{{ route('admin.coupons.update', $coupon->coupon_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="button-header mb-3">
            <button>
                Chỉnh sửa phiếu giảm giá <i class="fa fa-star"></i>
            </button>
            <div class="custom-btn-action d-flex p2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-info btn-sm mr-1"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Hủy</a>
                <button type="submit" class="btn btn-primary btn-sm"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Lưu</button>
            </div>
        </div>
        <!-- phiếu -->
        <div class="form-section">
            <div class="row gx-2 mb-3">
                <div class="col-6">
                    <label for="tenMaGiamGia" class="custom-label">Tên mã giảm giá <span
                            class="text-danger">*</span></label>
                    <input type="text" id="tenMaGiamGia" value="{{$coupon->code}}" name="code"
                        placeholder="Nhập tên mã giảm giá" class="form-control" />
                    @error('code')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="tenMaGiamGia" class="custom-label">Phân loại giảm giá <span
                            class="text-danger">*</span></label>
                    <select class="form-select" id="value" aria-label="Default select example">
                        @if($coupon->discount_amount)
                        <option value="1" selected>Số tiền giảm giá</option>
                        <option value="2">Phần trăm giảm giá</option>
                        @elseif($coupon->discount_percentage)
                        <option value="2" selected>Phần trăm giảm giá</option>
                        <option value="1">Số tiền giảm giá</option>
                        @else
                        <option selected>Chọn loại giảm giá</option>
                        <option value="1">Số tiền giảm giá</option>
                        <option value="2">Phần trăm giảm giá</option>
                        @endif
                    </select>
                </div>
                <div class="row gx-2 my-3" id="value1">
                    <label for="discount" class="custom-label">Ưu đãi giảm giá đơn hàng <span
                            class="text-danger">*</span></label>
                    @if($coupon->discount_amount)
                    <input type="text" class="form-control"
                        value="{{ number_format($coupon->discount_amount, 0, ',', '') }}" name="discount_amount"
                        id="discount" placeholder="Nhập điều kiện áp dụng" />
                    @elseif($coupon->discount_percentage)
                    <input type="text" class="form-control" value="{{ $coupon->discount_percentage }}"
                        name="discount_percentage" id="discount" placeholder="Nhập điều kiện áp dụng" />
                    @else
                    <input type="text" class="form-control" id="discount" placeholder="Nhập điều kiện áp dụng" />
                    @endif
                    @error('discount_amount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                    @error('discount_percentage')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

            </div>
            <div class="row gx-2 mb-3">
                <div class="col-4">
                    <label for="condition" class="custom-label">Giá trị đơn hàng tối thiểu <span
                            class="text-danger">*</span></label>
                    <input type="number" id="condition"
                        value="{{ number_format($coupon->min_order_value, 0, ',', '') }}" name="min_order_value"
                        placeholder="Nhập điều kiện áp dụng" class="form-control" />
                </div>
                @error('min_order_value')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="col-4">
                    <label for="max_order_value" class="custom-label">Giá trị đơn hàng tối đa <span
                            class="text-danger">*</span></label>
                    <input type="number" id="max_order_value"
                        value="{{ number_format($coupon->max_order_value, 0, ',', '') }}" name="max_order_value"
                        placeholder="Nhập giá trị tối đa" class="form-control" />
                    @error('max_order_value')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="quantity" class="custom-label">Số lượng mã giảm giá<span
                            class="text-danger">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{$coupon->quantity}}"
                        placeholder="Nhập số lượng" class="form-control" />
                    @error('quantity')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="row gx-2 mb-3">
                <div class="col-6">
                    <label for="start_date" class="custom-label">Bắt đầu ưu đãi <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date"
                        value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') }}" id="start_date"
                        class="form-control" />
                    @error('start_date')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="end_date" class="custom-label">Kết thúc ưu đãi <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local"
                        value="{{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') }}" name="end_date"
                        id="end_date" class="form-control" />
                    @error('end_date')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="row gx-2 mb-3 radio-group">
                <label>Chọn kiểu <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input type="radio" name="is_public" id="public" value="0" class="form-check-input" checked>
                    <label for="public" class="form-check-label">Public</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="is_public" id="private" value="1" class="form-check-input">
                    <label for="private" class="form-check-label">Private</label>
                </div>
                @error('is_public')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
        </div>
        <!-- table -->

        <div class="customer-section-list" style="display:none" id="customer-section" style="display: {{ $coupon->is_public == 0 ? 'none' : 'block' }};">
            <div class="button-header mb-3">
                <button>
                    Danh sách khách hàng <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="search-group mb-3">
                <input type="text" placeholder="Tìm kiếm khách hàng" name="nhap" class="form-control" />
                <button class="btn btn-primary" name="action" value="search_user">Tìm</button>
            </div>
            <table class="product-table table table-bordered text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" /></th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                    </tr>
                </thead>
                <tbody class="table-scrollable">


                </tbody>
            </table>
        </div>

        </div>

        @error('user_id')
        <span class="text-danger">{{$message}}</span>
        @enderror

    </form>
</body>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử
    var privateRadio = document.getElementById('private');
    var publicRadio = document.getElementById('public');
    var customerSection = document.getElementById('customer-section');

    // Kiểm tra giá trị is_public để xác định trạng thái ban đầu
    var isPublicValue = {
        {
            $coupon - > is_public
        }
    }; // giá trị từ controller (0 hoặc 1)

    if (isPublicValue === 1) {
        // Nếu is_public = 0 (Private), ẩn customer section
        customerSection.style.display = "block";
        privateRadio.checked = true; // Đánh dấu private là checked
    } else {
        // Nếu is_public = 1 (Public), hiển thị customer section
        customerSection.style.display = "none";
        publicRadio.checked = true; // Đánh dấu public là checked
    }

    // Xử lý khi click vào Private radio button
    privateRadio.addEventListener('click', function() {
        customerSection.style.display = "block"; // Ẩn danh sách khách hàng
    });

    // Xử lý khi click vào Public radio button
    publicRadio.addEventListener('click', function() {
        customerSection.style.display = "none"; // Hiển thị danh sách khách hàng
    });
});



value = document.getElementById('value');
discount = document.getElementById('discount');

value.addEventListener('change', function() {
    if (value.value == 1) {

        discount.setAttribute('placeholder', 'Nhập số tiền giảm giá')
        discount.setAttribute('name', 'discount_amount')
        discount.setAttribute('value', '')
    } else {

        discount.setAttribute('placeholder', 'Nhập phần trăm giảm giá')
        discount.setAttribute('name', 'discount_percentage')
        discount.setAttribute('value', '')
    }
});
// private = document.getElementById('private');
// public = document.getElementById('public');
// customer = document.getElementById('customer-section');
// private.addEventListener('click', function () {
//     customer.style.display = "block";
// });
// public.addEventListener('click', function () {
//     customer.style.display = "none";
// });

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector("input[name='nhap']");
    const searchButton = document.querySelector("button[name='action'][value='search_user']");
    const tableBody = document.querySelector("tbody.table-scrollable");
    const couponId = {
        {
            $couponId
        }
    }; // Truyền từ controller vào JavaScript

    let usersData = []; // Biến lưu trữ tất cả người dùng

    // Lấy toàn bộ người dùng và hiển thị ngay khi trang tải
    fetch(`{{ route('admin.coupons.searchId', ['id' => $couponId]) }}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Kiểm tra dữ liệu trả về
            if (data.users) {
                // Lưu dữ liệu người dùng vào `usersData` và hiển thị
                usersData = data.users;
                displayUsers(usersData, data.userCouponIds);
            } else {
                console.error("Không thể tải dữ liệu người dùng!");
            }
        })
        .catch(error => {
            console.error("Lỗi khi tải dữ liệu:", error);
        });

    // Hàm hiển thị danh sách người dùng
    function displayUsers(users, userCouponIds) {
        tableBody.innerHTML = ""; // Xóa dữ liệu cũ

        users.forEach(user => {
            const isChecked = userCouponIds.includes(user
                .user_id); // Kiểm tra xem người dùng có trong danh sách chọn
            const row = `
                <tr>
                    <td>
                        <input type="checkbox" name="user_id[]" value="${user.user_id}" ${isChecked ? 'checked' : ''} />
                    </td>
                    <td>${user.name}</td>
                    <td>${user.phone || "N/A"}</td>
                    <td>${user.email || "N/A"}</td>
                    <td>${user.address || "N/A"}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // Bước 3: Thêm sự kiện click cho nút "Tìm kiếm"
    searchButton.addEventListener("click", function(event) {
        event.preventDefault(); // Ngăn form gửi đi
        const query = searchInput.value.trim();

        if (query === "") {
            alert("Vui lòng nhập từ khóa tìm kiếm!");
            return;
        }

        // Lọc dữ liệu từ mảng `usersData`
        const filteredUsers = usersData.filter(user =>
            user.name.toLowerCase().includes(query.toLowerCase())
        );

        // Hiển thị kết quả tìm kiếm
        if (filteredUsers.length > 0) {
            displayUsers(filteredUsers, []); // Giả sử bạn không cần userCouponIds cho phần tìm kiếm
        } else {
            alert("Không tìm thấy kết quả!");
            tableBody.innerHTML = ""; // Xóa bảng nếu không tìm thấy
        }
    });
});
</script>
@endpush
</body>



@endsection
