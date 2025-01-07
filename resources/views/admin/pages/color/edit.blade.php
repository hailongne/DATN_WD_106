@extends('admin.index')
@section('content')
<style>
.table td.color-code {
    color: #fff;
    font-weight: bold;
}
</style>
<style>
.round-color-picker {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 100%;
    cursor: pointer;
}

#color-display {
    margin-left: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
}

.d-flex {
    display: flex;
    align-items: center;
}

.mr-2 {
    margin-right: 8px;
}
</style>
<div class="container mt-5">
<form action="{{route('admin.colors.update', $color->color_id)}}" method="POST" class="custom-form-container"
    enctype="multipart/form-data">
        <div class="button-header mb-3">
            <button>
                Chỉnh sửa màu sắc <i class="fa fa-star"></i>
            </button>
        </div>
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name" class="custom-label">Tên màu sắc <span class="custom-required-star">*</span></label>
        <input type=" text" class="form-control" id="name" name="name" value="{{$color->name}}"
            placeholder="Nhập tên màu sắc"  />
    </div>
    @error('name')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="form-group">
        <label for="color_code" class="custom-label">Mã màu <span class="custom-required-star">*</span></label>
        <div class="d-flex align-items-center">
            <input type="color" class="round-color-picker mr-2" id="color_code" name="color_code"
                onchange="updateColorDisplay()" value="{{$color->color_code}}" />
            <span id="color-display">{{$color->color_code}}</span>
        </div>
    </div>
    @error('color_code')
<span class="text-danger">{{$message}}</span>
@enderror
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.colors.index') }}" class="btn btn-info">Hủy</a>
    </div>
</form>
</div>
<script>
function updateColorDisplay() {
    var colorPicker = document.getElementById('color_code');
    var colorDisplay = document.getElementById('color-display');
    colorDisplay.textContent = colorPicker.value;
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    @push('scripts')
    @endpush

    @endsection
