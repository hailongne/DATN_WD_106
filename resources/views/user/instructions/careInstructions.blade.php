@extends('user.index')
<link href="{{ asset('css/instructions/careInstructions.css') }}" rel="stylesheet" type="text/css">
@section('content')

<div class="flex mt-10">
    <div class="w-1/4 p-4">
        <div class="border p-4">
            <h2 class="font-bold mb-4">DANH MỤC TRANG</h2>
            <hr class="border-black border-t-2 mt-1" />
            <ul style="list-style-type: none">
                <li class="mb-2">
                    <a href="{{ url('/care-instruction') }}" class="{{ request()->is('care-instruction') ? 'text-primary' : 'text-black'}}">Hướng dẫn bảo quản</a>
                </li>
                <li class="mb-2">
                    <a href="{{ url('/customer-instruction') }}" class="text-black">Khách hàng thân thiết</a>
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
    <div class="w-3/4 p-4">
        <h2 class="text-3xl font-bold text-blue-300 mb-4 text-center">HƯỚNG DẪN GIẶT ỦI</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-300 text-white p-4">
                <h3 class="font-bold text-center text-2xl">Quần áo một trơn 1 màu:</h3>
            </div>
            <div class="bg-blue-300 text-white p-4 h-auto">
                <p>- Lần giặt đầu tiên không nên giặt ngay, để tránh bay màu và mòn vải. Nên cuộn lại vào trong áo gối và để vào ngăn đá tủ lạnh 1-2 tiếng, sau đó giặt bình thường. Sản phẩm được đảm bảo bền màu và bền sớ vải.</p>
                <p>- Lần giặt tiếp theo nên sử dụng các loại xà phòng (không có chất tẩy hoặc độ tẩy nhẹ) như dầu gội đầu, sữa tắm, bột xà phòng thơm... sản phẩm sẽ bền màu và giặt rất nhanh.</p>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <h3 class="font-bold text-lg">Với các mẫu có phối vải màu khác nhau trên cùng 1 sản phẩm (nhất là màu tối):</h3>
            </div>
            <div class="bg-blue-300 text-white p-4 h-auto">
                <p>- Chất liệu này 3 nước đầu không nên giặt chung với các sản phẩm khác, nếu có hãy ngâm vào túi đựng riêng 1-2 giờ đầu rồi mới giặt.</p>
                <p>- Lưu ý: <br />+ KHÔNG giặt tẩy bằng các chất tẩy mạnh <br />+ KHÔNG ngâm nước quá lâu. <br />+ KHÔNG nên ngâm và giặt chung với các sản phẩm khác - tránh trường hợp màu đậm sẽ phai màu và làm xấu sản phẩm khác khi giặt chung.</p>
                <p>- Cách giặt đồ: lấy 1-2 giọt sữa tắm, dầu gội đầu... đánh bông lên cho sản phẩm vào bóp nhẹ, rồi sau đó giặt lại bằng nước sạch là phải lên khăn, tránh ngâm lâu.</p>
            </div>
            <div class="bg-blue-300 text-white p-4 h-auto">
                <h3 class="font-bold text-lg">Với những sản phẩm chất liệu ren, có phụ kiện:</h3>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <p>- Không nên giặt sản phẩm cùng với các sản phẩm có khối lượng nặng khác, có móc cài, có nhiều hoa tiết... sẽ làm ảnh hưởng đến chất liệu sản phẩm. (Sản phẩm ren và lưới hoặc vải mỏng nên giặt bằng tay, nếu giặt máy vui lòng bỏ vào túi giặt).</p>
            </div>
            <div class="bg-blue-300 text-white p-4 h-auto">
                <h3 class="font-bold text-lg">Với những sản phẩm JEAN, KAKI:</h3>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <p>- Chất liệu này 3 nước đầu không nên giặt chung với các sản phẩm khác, nếu có hãy ngâm vào túi đựng riêng 1-2 giờ đầu rồi mới giặt.</p>
            </div>
            <div class="bg-blue-300 text-white p-4 h-auto">
                <h3 class="font-bold text-lg">Sản phẩm có độ co giãn :</h3>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <p>Khi phơi nên phơi ngang và lộn mặt trái của sản phẩm để phơi</p>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <h3 class="font-bold text-lg">Tất cả các sản phẩm</h3>
            </div>
            <div class="bg-blue-300 text-white p-4">
                <p>- Nên phơi quần áo trong bóng mát, không nên phơi dưới ánh nắng gắt để tránh mất màu sản phẩm. <br />- Những sản phẩm có in hình nên lộn mặt trái và phơi dưới ánh nắng nhẹ hoặc phơi trong bóng mát. Không giặt quần áo bằng nước ấm hoặc nóng. <br />- Sản phẩm có gắn kim loại hoặc có đính ngọc nên giặt bằng tay hoặc giặt máy bằng túi giặt riêng. Sản phẩm có nhiệt độ vừa phải.</p>
            </div>
        </div>
    </div>
</div>
@endsection