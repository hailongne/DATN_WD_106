@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{asset('css/style.css')}}">
@endpush
@section('content')


<body>
    <div class="container">
        <h1 class="text-center">Danh Sách đợt giảm giá</h1>
        <a href="{{route('admin.promotionPeriods.create')}}"><button class="btn add-button">Thêm mới</button></a>

        <table class="product-table table table-bordered text-center align-middle mb-5">
            <thead class="thead-dark">
                <tr>
                    <th>STT</th>
                    <th>Tên mã phiếu giảm giá</th>
                    <th>Loại</th>
                    <th>Ngày bắt đầu </th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($promPers as $key => $promPer)
                <tr>
                    <td>{{$promPer->prom_per_id}}</td>
                    <td>{{$promPer->code}}</td>
                    @if($promPer->discount_amount)
                    <td>{{$promPer->discount_amount}}</td>
                    @else
                    <td>{{$promPer->discount_percentage	}}</td>
                    @endif
                    <td>{{$promPer->start_date}}</td>
                    <td>{{$promPer->end_date}}</td>
                    <td>
                        <form action="{{ route('admin.promotionPeriods.toggle', $promPer->prom_per_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn {{ $promPer->is_active ? 'btn-danger' : 'btn-success' }}">
                            <p>{{ $promPer->is_active ? 'Đang hoạt động' : 'Đã tắt hoạt động' }}</p>
                            </button>
                        </form>
                    </td>
                    <td class="action-icons">
                        <a href="{{route('admin.promotionPeriods.detail',$promPer->prom_per_id)}}"> <i
                                class="fas fa-eye text-info" title="Chi tiết"></i></a>
                        <a href="{{route('admin.promotionPeriods.edit',$promPer->prom_per_id)}}"><i
                                class="fas fa-edit text-warning" title="Sửa"></i></a>
                        <!-- Form xóa -->
                        <form action="{{ route('admin.promotionPeriods.delete', $promPer->prom_per_id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu sắc này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @endforeach


            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
    </script>
    @endpush
</body>
@endsection
