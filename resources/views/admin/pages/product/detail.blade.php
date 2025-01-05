<link rel="stylesheet" href="{{ asset('css/huongDetail.css') }}">
<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên Sản Phẩm</th>
            <th>Kích cỡ</th>
            <th>Màu</th>
            <th>Giá</th>
            <th>Số lượng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attPros as $attPro )
        <tr>
            <td>{{$attPro->product->sku}}</td>
            <td>{{$attPro->product->name}}</td>
            <td>{{$attPro->size->name}}</td>
            <td>{{$attPro->color->name}}</td>
            <td>{{ number_format($attPro->price, 0, ',', '.') }} đ</td>
            <td>{{$attPro->in_stock}}</td>
        </tr>

        @endforeach
        <!-- Có thể thêm nhiều hàng hơn ở đây -->
    </tbody>
</table>