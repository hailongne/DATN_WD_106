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
<form action="{{route('admin.colors.store')}}" method="POST" class="custom-form-container"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name" class="custom-label">Tên màu sắc <span class="custom-required-star">*</span></label>
        <input type=" text" class="form-control" id="name" name="name" value="{{$color->name}}"
            placeholder="Nhập tên thương hiệu" required />
    </div>
    <div class="form-group">
        <label for="color_code" class="custom-label">Mã màu <span class="custom-required-star">*</span></label>
        <div class="d-flex align-items-center">
            <input type="color" class="round-color-picker mr-2" id="color_code" name="color_code"
                onchange="updateColorDisplay()" value="{{$color->color_code}}" />
            <span id="color-display">#000000</span>
        </div>
    </div>
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Thêm mới</button>
    </div>
</form>
<script>
function updateColorDisplay() {
    var colorPicker = document.getElementById('color_code');
    var colorDisplay = document.getElementById('color-display');
    colorDisplay.textContent = colorPicker.value;
}
</script>