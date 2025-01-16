@extends('user.index')
<link href="{{ asset('css/instructions/returnInstructions.css') }}" rel="stylesheet" type="text/css">
@section('content')

<div class="p-4">
    <div class="flex">
        <div class="w-1/4 p-4">
            <div class="border p-4">
                <h2 class="font-bold mb-4">DANH MỤC TRANG</h2>
                <hr class="border-black border-t-2 mt-1" />
                <ul style="list-style-type: none">
                    <li class="mb-2">
                        <a href="{{ url('/care-instruction') }}" class="text-black">Hướng dẫn bảo quản</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/customer-instruction') }}" class="text-black">Khách hàng thân thiết</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/return-instruction') }}" class="{{ request()->is('return-instruction') ? 'text-primary' : 'text-black'}}">Chính sách đổi hàng</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/buy-instruction') }}" class="text-black">Hướng dẫn mua hàng</a>
                    </li>
                </ul>
                <hr class="border-black mt-4" />
            </div>
        </div>
        <div class="w-3/4 pl-4">
            <h1 class="text-xl font-bold mb-4">Chính sách đổi hàng</h1>

            <div class="bg-blue-500 text-white p-4 mb-4">
                <h2 class="text-center text-lg font-bold">HƯỚNG DẪN ĐỔI HÀNG</h2>
                <div class="text-center mt-2">
                    <i class="fas fa-shopping-bag text-4xl"></i>
                </div>
            </div>
            <div class="mb-4">
                <h3 class="font-bold text-blue-500">ĐIỀU KIỆN ĐỔI HÀNG</h3>
                <ul class="list-disc pl-5">
                    <li>Thời gian đổi trong 7 ngày, kể từ ngày xuất hóa đơn.</li>
                    <li>Có hóa đơn mua hàng, còn nguyên tag giá.</li>
                    <li>Chưa qua sử dụng, giặt ủi, có mùi lạ, chưa cắt khuy...</li>
                    <li>Đổi sản phẩm có giá trị bằng hoặc lớn hơn. Sản phẩm đổi có giá trị thấp hơn, sẽ không hoàn lại tiền thừa.</li>
                    <li>Thời gian đổi hàng từ 14h - 22h</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3 class="font-bold text-blue-500">ĐỔI HÀNG ONLINE</h3>
                <ul class="list-disc pl-5">
                    <li>Gọi vào tổng đài (028) 7300 6200 hoặc inbox qua Fanpage facebook.com/gentlemanorvietnam để được hướng dẫn.</li>
                    <li>Quý khách vui lòng QUAY LẠI VIDEO đóng hàng và gửi lại shop Video, để Gentlemanor đối chiếu với đơn vị vận chuyển trong trường hợp thất lạc hàng hoặc có vấn đề phát sinh.</li>
                    <li>Khi shipper giao hàng quý khách vui lòng gửi lại hàng đổi và nhận hàng mới. Sau đó thanh toán phí ship đổi hàng (HCM 30k - tỉnh thành 45k) là xong ạ.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3 class="font-bold text-blue-500">LƯU Ý</h3>
                <ul class="list-disc pl-5">
                    <li>Một hóa đơn chỉ được đổi 1 lần.</li>
                    <li>Sản phẩm khuyến mãi, hàng phụ kiện không hỗ trợ chính sách đổi hàng.</li>
                    <li>Kenta chỉ hỗ trợ đổi hàng, không trả hàng / hoàn tiền.</li>
                    <li>Miễn phí vận chuyển 2 chiều nếu sản phẩm bị lỗi từ nhà sản xuất, shop giao nhầm hàng, nhầm màu...</li>
                    <li>Quý khách vui lòng hỗ trợ phí ship 2 chiều khi: không thích màu, đổi size, muốn đổi sản phẩm khác...</li>
                </ul>
            </div>
            <div class="text-center bg-gray-200 p-4">
                <p>Cảm ơn bạn đã lựa chọn GENTLEMANOR</p>
            </div>
        </div>
    </div>
</div>
@endsection