@extends('admin.index')

@section('content')
    <h1>Quản lý Banner</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Thêm Banner Mới</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Link</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td><img src="{{ Storage::url('banners/' . $banner->image_url) }}" alt="Banner Image" width="100"></td>
                    <td>{{ $banner->link }}</td>
                    <td>{{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
