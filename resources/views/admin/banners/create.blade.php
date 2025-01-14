@extends('admin.index')

@section('content')
    <h1>Thêm Banner</h1>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type="text" name="link" class="form-control" placeholder="Link banner (tùy chọn)">
        </div>
        <button type="submit" class="btn btn-success">Lưu Banner</button>
    </form>
@endsection
