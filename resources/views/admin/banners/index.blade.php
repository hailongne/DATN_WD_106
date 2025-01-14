@extends('admin.index')

@section('content')
<style>
.img-banner-admin-manager {
    width: 100% !important;
}
</style>
<div class="button-header mb-2 ">
    <button>Quản lý Banner <i class="fa fa-star"></i></button>
    @if(Auth::user()->role !== 3)
    <a href="{{ route('admin.banners.create') }}" class="btn add-button">Thêm mới</a>
    @endif
</div>
@php
$index = 1; // Bắt đầu đếm từ 1
@endphp
<table class="product-table table table-bordered text-center align-middle mb-5">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Hình ảnh</th>
            <th>Link</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banners as $banner)
        <tr>
            <td>{{ $index++ }}</td>
            <td><img src="{{ asset('storage/' . $banner->image_url) }}" class="img-banner-admin-manager"
                    alt="Banner Image" width="100"></td>
            <td>{{ $banner->link }}</td>
            <td>{{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
            <td>
                <div class="icon-product d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-warning">
                        <button class="action-btn edit" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                    </a>
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');
                        style=" display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete">
                            <i class="fas fa-trash-alt" title="Xóa"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
