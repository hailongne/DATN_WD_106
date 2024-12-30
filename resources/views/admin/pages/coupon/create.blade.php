@extends('admin.index')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/huongCoupon.css')}}">
@endpush
@section('content')

<body>
    <form action="{{ route('admin.coupons.store') }}" method="POST" class="custom-form-container"
        enctype="multipart/form-data">
        @csrf
        <div class="container">
            <!-- phiếu -->
            <div class="form-section">
                <h1>Phiếu Giảm Giá</h1>
                <div class="form-group">
                    <label for="tenMaGiamGia">Tên mã giảm giá:</label>
                    <input type="text" id="tenMaGiamGia" name="code" placeholder="Nhập tên mã giảm giá" />
                </div>
                @error('code')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="tenMaGiamGia">Gía trị giảm giá</label>
                    <select class="form-select" id="value" aria-label="Default select example">
                        <option selected>Chọn loại giảm giá</option>
                        <option value="1">Số tiền giảm giá</option>
                        <option value="2">Phần trăm giảm giá</option>

                    </select>
                </div>
                <div class="form-group" id="value1" style="display: none;">
                    <label for="discount">Gía trị</label>
                    <input type="number" id="discount" placeholder="Nhập điều kiện áp dụng" />
                </div>
                @error('discount_amount')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                @error('discount_percentage')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="condition">Gía trị tối thiểu</label>
                    <input type="number" id="condition" name="min_order_value" placeholder="Nhập điều kiện áp dụng" />
                </div>
                @error('min_order_value')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="max_order_value">Giá trị tối đa:</label>
                    <input type="number" id="max_order_value" name="max_order_value"
                        placeholder="Nhập giá trị tối đa" />
                </div>
                @error('max_order_value')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" placeholder="Nhập số lượng" />
                </div>
                @error('quantity')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="start_date">Thời gian từ ngày:</label>
                    <input type="datetime-local" name="start_date" id="start_date" />
                </div>
                @error('start_date')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">
                    <label for="end_date">Thời gian đến ngày:</label>
                    <input type="datetime-local" name="end_date" id="end_date" />
                </div>
                @error('end_date')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form-group">

                    <label>Chọn kiểu</label>
                    <div class="form-check">
                        <input type="radio" name="is_public" id="public" value="1" class="form-check-input" checked>
                        <label for="public" class="form-check-label">Public</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="is_public" id="private" value="0" class="form-check-input">
                        <label for="private" class="form-check-label">Private</label>
                    </div>
                </div>
                @error('is_public')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <!-- table -->
            <div class="customer-section" style="display:none" id="customer-section">
                <h2>Danh sách khách hàng</h2>
                <div class="search-group">
                    <i class="fas fa-search icon"></i>
                    <input type="text" placeholder="Tìm kiếm khách hàng" />
                    <button>Tìm kiếm</button>
                </div>
                <table class="customer-list">
                    <thead>
                        <tr>
                            <th><input type="checkbox" /></th>
                            <th>Tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @if($user->user_id != Auth::user()->user_id || $user->role !=1)
                            <tr>
                                <td class="checkbox">
                                    <input type="checkbox" name="user_id[]" value="{{ $user->user_id }}" />
                                </td>
                                <td>{{ $user->name}}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->address }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="add-button">
            <button type="submit">Thêm mới</button>
        </div>
        <!-- thêm -->

    </form>
</body>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        value = document.getElementById('value');
        discount = document.getElementById('discount');

        value.addEventListener('change', function () {
            if (value.value == 1) {
                document.getElementById('value1').style.display = "block";
                discount.setAttribute('placeholder', 'Nhập số tiền giảm giá')
                discount.setAttribute('name', 'discount_amount')
            }
            else {
                document.getElementById('value1').style.display = "block";
                discount.setAttribute('placeholder', 'Nhập phần trăm giảm giá')
                discount.setAttribute('name', 'discount_percentage')
            }
        });
        private = document.getElementById('private');
        public = document.getElementById('public');
        customer = document.getElementById('customer-section');
        private.addEventListener('click', function () {
            customer.style.display = "block";
        });
        public.addEventListener('click', function () {
            customer.style.display = "none";
        });
    </script>
@endpush
</body>
@endsection