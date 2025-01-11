@extends('admin.index')
@section('content')

<body>
    <form action="{{ route('admin.coupons.store') }}" method="POST" class="custom-form-container"
        enctype="multipart/form-data">
        @csrf
        <div class="button-header mb-3">
            <button>
                Thêm mới phiếu giảm giá <i class="fa fa-star"></i>
            </button>
            <div class="custom-btn-action d-flex p2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-info btn-sm mr-1"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Hủy</a>
                <button type="submit" id="submit" class="btn btn-primary btn-sm"
                    style="height: 30px; line-height: 30px; width: auto; font-size: 12px; padding: 0 10px;">Tiếp
                    tục</button>
            </div>
        </div>
        <!-- phiếu -->
        <div class="form-section">
            <div class="row gx-2 mb-3">
                <div class="col-6">
                    <label for="tenMaGiamGia" class="custom-label">Tên mã giảm giá <span
                            class="text-danger">*</span></label>
                    <input type="text" id="tenMaGiamGia" value="{{old('code')}}" name="code"
                        placeholder="Nhập tên mã giảm giá" class="form-control" />
                    @error('code')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="tenMaGiamGia" class="custom-label">Giá trị giảm giá <span
                            class="text-danger">*</span></label>
                    <select class="form-select" id="value" aria-label="Default select example">
                        <option selected>Chọn loại giảm giá</option>
                        <option value="1">Số tiền giảm giá</option>
                        <option value="2">Phần trăm giảm giá</option>

                    </select>
                </div>
                <div class="row gx-2 mb-3" id="value1">
                    <label class="custom-label" for="discount">Gía trị</label>
                    <input type="number" class="form-control" id="discount"
                        name="{{ old('discount_percentage') ? 'discount_percentage' : 'discount_amount' }}"
                        value="{{ old('discount_amount') ?? old('discount_percentage') }}"
                        placeholder="Nhập số tiền giảm giá" />

                </div>
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
                <label for="condition" class="custom-label">Giá trị tối thiểu <span class="text-danger">*</span></label>
                <input type="number" id="condition" value="{{old('min_order_value')}}" name="min_order_value"
                    placeholder="Nhập điều kiện áp dụng" class="form-control" />
            </div>
            @error('min_order_value')
                <span class="text-danger">{{$message}}</span>
            @enderror
            <div class="col-4">
                <label for="max_order_value" class="custom-label">Giá trị tối đa <span
                        class="text-danger">*</span></label>
                <input type="number" id="max_order_value" value="{{old('max_order_value')}}" name="max_order_value"
                    placeholder="Nhập giá trị tối đa" class="form-control" />
                @error('max_order_value')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="col-4">
                <label for="quantity" class="custom-label">Số lượng <span class="text-danger">*</span></label>
                <input type="number" id="quantity" name="quantity" value="{{old('quantity')}}"
                    placeholder="Nhập số lượng" class="form-control" />
                @error('quantity')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="row gx-2 mb-3">
            <div class="col-6">
                <label for="start_date" class="custom-label">Thời gian từ ngày <span
                        class="text-danger">*</span></label>
                <input type="datetime-local" value="{{old('start_date')}}" name="start_date" id="start_date"
                    class="form-control" />

                @error('start_date')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="col-6">
                <label for="end_date" class="custom-label">Thời gian đến ngày <span class="text-danger">*</span></label>
                <input type="datetime-local" value="{{old('end_date')}}" name="end_date" id="end_date"
                    class="form-control" />
            </div>
            @error('end_date')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="row gx-2 mb-3">
            <label>Chọn kiểu <span class="text-danger">*</span></label>
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
        <div class="customer-section-list" style="display:none" id="customer-section">
            <div class="button-header mb-3">
                <button>
                    Danh sách khách hàng <i class="fa fa-star"></i>
                </button>
            </div>
            <div class="search-group mb-3">
                <input type="text" placeholder="Tìm kiếm khách hàng" name="nhap" class="form-control" />
                <button class="btn btn-primary" id="search-user" type="button">Tìm</button>
            </div>
            <table class="product-table table table-bordered text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                    </tr>
                </thead>
                <tbody class="table-scrollable">
                    @foreach ($users as $user)
                        @if($user->user_id != Auth::user()->user_id && $user->role != 1)
                            <tr>
                                <td><input type="checkbox" name="user_id[]" value="{{ $user->user_id }}" /></td>
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
                discount.setAttribute('value', '')
            } else {
                document.getElementById('value1').style.display = "block";
                discount.setAttribute('placeholder', 'Nhập phần trăm giảm giá')
                discount.setAttribute('name', 'discount_percentage')
                discount.setAttribute('value', '')
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
        submit = document.getElementById('submit');
        search = document.getElementById('search-user');
        search.addEventListener('click', function (e) {

            e.preventDefault();  // Ngăn chặn form submit
        })
    </script>

@endpush

</body>
@endsection