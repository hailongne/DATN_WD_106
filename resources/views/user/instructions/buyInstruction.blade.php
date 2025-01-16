@extends('user.index')
<link href="{{ asset('css/instructions/buyInstruction.css') }}" rel="stylesheet" type="text/css">
@section('content')

<div class="p-4">
    <div class="flex">
        <div class="w-1/4 p-4">
            <div class="border p-4">
                <h2 class="font-bold mb-4 text-blue-500">DANH MỤC TRANG</h2>
                <hr class="border-black border-t-2 mt-1" />
                <ul style="list-style-type: none">
                    <li class="mb-2">
                        <a href="{{ url('/care-instruction') }}" class="text-black">Hướng dẫn bảo quản</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/customer-instruction') }}" class="text-black">Khách hàng thân thiết</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/return-instruction') }}" class="text-black">Chính sách đổi hàng</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/buy-instruction') }}" class="{{ request()->is('buy-instruction') ? 'text-primary' : 'text-black'}}">Hướng dẫn mua hàng</a>
                    </li>
                </ul>
                <hr class="border-black mt-4" />
            </div>
        </div>
        <div class="w-3/4 pl-4">
            <h1 class="text-3xl font-bold mb-4 text-center text-blue-500">Hướng dẫn mua hàng</h1>
            <p class="mb-4">
                GENTLEMANOR nhận giao hàng toàn quốc. Bạn có thể mua hàng trực tiếp tại shop hoặc đặt hàng trên Website chính thức www.GENTLEMANOR.vn theo các bước sau:
            </p>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 1: TÌM SẢN PHẨM MONG MUỐN</h2>
            <p class="mb-4 text-black">Bạn có thể tìm kiếm bằng 2 cách như sau:</p>
            <ul class="list-disc list-inside mb-4">
                <li>Tìm kiếm theo tên/ mã sản phẩm: nhập vào biểu tượng kính lúp ở góc phải, nhập từ khoá tên/ mã tìm kiếm và ấn enter hoặc click vào biểu tượng kính lúp.</li>
                <li>Tìm kiếm theo nhóm sản phẩm: Click vào danh mục sản phẩm trên menu chính, các mục sản phẩm bao gồm: ÁO KHOÁC, ÁO THUN, SƠ MI, QUẦN DÀI, QUẦN SHORT xuất hiện. Click vào từng mục để hiện ra chi tiết sản phẩm bạn mong muốn.</li>
            </ul>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 2: THÊM SẢN PHẨM CẦN MUA VÀO GIỎ HÀNG</h2>
            <p class="mb-4">Sau khi đã tìm được sản phẩm mong muốn bạn cần tham khảo đầy đủ hình ảnh, mô tả kèm theo, hãy thực hiện thao tác chọn size, số lượng cần mua và click CHỌN THÊM VÀO GIỎ HÀNG để thêm sản phẩm vào giỏ hàng hoặc MUA NGAY.</p>
            <p class="mb-4">Giỏ hàng của bạn hiện lên danh sách sản phẩm bạn đang chọn và tổng giá trị đơn hàng. Click XEM GIỎ HÀNG nếu muốn kiểm tra giỏ hàng, Click THANH TOÁN nếu đã chọn xong sản phẩm và muốn mang món hàng về nhà: Click ký hiệu X để tiếp tục mua hàng.</p>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 3: KIỂM TRA GIỎ HÀNG VÀ TIẾN HÀNH ĐẶT HÀNG</h2>
            <ul class="list-disc list-inside mb-4">
                <li>Kiểm tra lại thông tin đầy đủ về sản phẩm muốn đặt mua.</li>
                <li>Điền mã giảm giá (nếu có) vào ô khung MÃ GIẢM GIÁ và Click SỬ DỤNG.</li>
                <li>Điền đầy đủ thông tin giao hàng của bạn bao gồm Họ và tên, Email, Số điện thoại, Địa chỉ. Nếu đã có đăng ký tài khoản từ trước hãy Click vào ĐĂNG NHẬP.</li>
                <li>Kiểm tra lại tất cả thông tin đã nhập, sau khi đã chắc chắn thì Click TIẾP TỤC ĐẾN PHƯƠNG THỨC THANH TOÁN để hoàn tất đơn hàng của bạn.</li>
            </ul>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 4: CHỌN PHƯƠNG THỨC VẬN CHUYỂN</h2>
            <p class="mb-4">Sau khi bạn nhập đầy đủ thông tin trong phần thông tin giao hàng, căn cứ vào địa chỉ nhận hàng và tổng giá trị đơn hàng, Website sẽ đưa ra cho bạn hình thức vận chuyển và chi phí vận chuyển để bạn lựa chọn.</p>
            <p class="mb-4 text-black">Mức phí vận chuyển theo từng khu vực như sau:</p>
            <h3 class="font-bold mb-2">2. Khu vực tỉnh thành khác:</h3>
            <ul class="list-disc list-inside mb-4">
                <li>Phí ship đồng giá 40k.</li>
                <li>Free ship cho đơn hàng tỉnh thành trên 500k (Khách hàng chuyển khoản trước).</li>
                <li>Thời gian nhận hàng từ 2-5 ngày làm việc không kể thứ 7 và chủ nhật.</li>
            </ul>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 5: CHỌN PHƯƠNG THỨC THANH TOÁN</h2>
            <p class="mb-4">Trong phần PHƯƠNG THỨC THANH TOÁN, bạn có thể thanh toán theo các hình thức sau:</p>
            <ul class="list-disc list-inside mb-4">
                <li>Thanh toán khi nhận hàng (COD).</li>
                <li>Chuyển khoản qua ngân hàng và thanh toán khi nhận hàng.</li>
            </ul>
            <p class="mb-4">Bạn chuyển khoản cho GENTLEMANOR ngay sau khi nhận được xác nhận đơn hàng thành công. Thông tin chuyển khoản nhận được Online sẽ hướng dẫn cụ thể.</p>
            <p class="mb-4">Sau khi chuyển khoản, bạn vui lòng xác nhận lại với GENTLEMANOR bằng cách gọi vào hotline: <strong>(028) 7300 6200</strong> hoặc để lại tin nhắn trên Fanpage của GENTLEMANOR và chờ nhân viên kiểm tra hoàn thành đơn hàng.</p>
            <h2 class="font-bold mb-2 text-blue-500">BƯỚC 6: HOÀN TẤT ĐƠN HÀNG</h2>
            <p class="mb-4">Bạn Click vào nút HOÀN TẤT ĐƠN HÀNG sau khi đã hoàn thành các bước trên và kiểm tra thật kỹ tất cả các thông tin đơn hàng, phương thức vận chuyển, phương thức thanh toán.</p>
            <p class="mb-4">Nếu có bất cứ vấn đề gì trong khi đặt hàng bạn vui lòng KIỂM TRA ĐƠN HÀNG & gọi vào hotline hoặc liên hệ trực tiếp tổng đài mua hàng <strong>(028) 7300 6200</strong> (giờ hành chính ngày làm việc).</p>
        </div>
    </div>
</div>
@endsection