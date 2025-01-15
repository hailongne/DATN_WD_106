@extends('admin.index')

@section('content')
    <h1>Sửa Banner</h1>

    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
            <img src="{{ asset('storage/' . $banner->image_url) }}" alt="Banner Image" width="100">
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type="text" name="link" class="form-control" value="{{ $banner->link }}">
        </div>
        <button type="submit" class="btn btn-success">Cập nhật Banner</button>
    </form>
@endsection
