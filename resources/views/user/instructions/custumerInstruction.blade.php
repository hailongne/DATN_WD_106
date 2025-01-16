@extends('user.index')
<link href="{{ asset('css/instructions/returnInstructions.css') }}" rel="stylesheet" type="text/css">
@section('content')

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
                    <a href="{{ url('/customer-instruction') }}" class="{{ request()->is('customer-instruction') ? 'text-primary' : 'text-black'}}">Khách hàng thân thiết</a>
                </li>
                <li class="mb-2">
                    <a href="{{ url('/return-instruction') }}" class="text-black">Chính sách đổi hàng</a>
                </li>
                <li class="mb-2">
                    <a href="{{ url('/buy-instruction') }}" class="text-black">Hướng dẫn mua hàng</a>
                </li>
            </ul>
            <hr class="border-black mt-4" />
        </div>
        
    </div>
    <div class="w-3/4 pl-4">
        <h1 class="text-2xl font-bold mb-4">Khách hàng thân thiết</h1>
        <h2 class="text-xl font-bold text-blue-500 mb-4">CHÍNH SÁCH KHÁCH HÀNG THÂN THIẾT</h2>
        <table class="w-full border-collapse border">
            <thead>
                <tr>
                    <th class="border p-2">QUYỀN LỢI THÀNH VIÊN</th>
                    <th class="border p-2">MỨC ĐIỂM TÍCH LŨY (50.000 đồng = 1 điểm)</th>
                    <th class="border p-2">MỨC GIẢM GIÁ</th>
                    <th class="border p-2">ƯU ĐÃI SINH NHẬT (1 lần giảm trong tháng sinh nhật)</th>
                    <th class="border p-2">ƯU ĐÃI KÈM THEO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border p-2">MEMBER</td>
                    <td class="border p-2">0 điểm - 60 điểm</td>
                    <td class="border p-2">5%</td>
                    <td class="border p-2">5%</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="border p-2">VIP 5%</td>
                    <td class="border p-2">61 điểm - 150 điểm</td>
                    <td class="border p-2">5%</td>
                    <td class="border p-2">10%</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="border p-2">VIP 10%</td>
                    <td class="border p-2">151 điểm - 250 điểm</td>
                    <td class="border p-2">10%</td>
                    <td class="border p-2">15%</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="border p-2">VIP 15%</td>
                    <td class="border p-2">251 điểm trở lên</td>
                    <td class="border p-2">15%</td>
                    <td class="border p-2">20%</td>
                    <td class="border p-2">Tặng quà khi sinh nhật</td>
                </tr>
            </tbody>
        </table>
        <p class="mt-4">Chương trình bắt đầu áp dụng từ 01/09/2024.</p>
        <p>
            Với mỗi hóa đơn mua hàng khách hàng đều được tham gia tích điểm với mức tích điểm là 
            <strong>50.000 vnd = 1 điểm</strong>. Một thẻ thành viên có thể được sử dụng cho người thân, bạn bè đến cùng tích điểm và cùng hưởng giảm giá.
        </p>
    </div>
</div>
@endsection