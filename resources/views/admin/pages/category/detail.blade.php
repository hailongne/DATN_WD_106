@extends('admin.index')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{asset('css/admin/formAddCategory.css')}}">
<style>
.cusstom-no-image {
    width: 50px !important;
    height: 50px !important;
}
</style>
<style>
.container {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.category-detail {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.brand-info {
    text-align: left;
    margin-top: 20px;
}

.image-container img {
    border-radius: 8px;
    border: 2px solid #ddd;
    margin-bottom: 10px;
}

strong {
    color: #333;
    font-weight: bold;
}

span {
    color: #555;
}
</style>
@endpush
<div class="container mt-5 ">
<div class="button-header">
        <button>
           Chi tiết danh mục <i class="fa fa-star"></i>
        </button>
    </div>
<div class="container">
    <div class="category-detail">
        <div class="brand-info">
            <div class="image-container">
                <img src="{{Storage::url($category->image)}}" width="150px" height="150px" alt="Ảnh danh mục">
            </div>

            <h3>
                <strong>Tên Danh mục:</strong>
                <span id="name">{{$category->name}}</span>
            </h3>

            <p>
                <strong>Mô Tả:</strong>
                <span id="description">{{$category->description}}</span>
            </p>

            <p>
                <strong>Tên Đường Dẫn:</strong>
                <span id="slug">{{$category->slug}}</span>
            </p>

            <p>
                <strong>Ngày Tạo:</strong>
                <span id="created_at">{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y H:i') }}</span>
            </p>

            <p>
                <strong>Ngày Cập Nhật:</strong>
                <span id="updated_at">{{ \Carbon\Carbon::parse($category->updated_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
    </div>
</div>
</div>
</script>
<!-- Thêm các Scripts cần thiết -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


@endsection