@extends('admin.index')
@section('content')
@push('styles')
<style>
    /* CSS */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px; /* Khoảng cách giữa các form */
    margin-top: 30px; /* Khoảng cách trên */
}

</style>
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
@endpush
<link rel="stylesheet" href="{{asset('css/admin/formAddProduct.css')}}">
<div class="container mt-5">
<form id="productForm" method="POST" action="{{route('admin.inventories.update', $attPro->attribute_product_id )}}"
    class="custom-form-container" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <!-- First Row -->
    <div class="row gx-2 mb-3">
        <div class="col-md-6">
            <label class="custom-label" for="in_stock">Cập nhật số lượng</label>
            <input type="text" class="form-control" id="in_stock" name="in_stock" placeholder="Cập nhật số lượng"
                required maxlength="50" value="{{$attPro->in_stock}}" />
                @error('in_stock')
                    <span class="text-danger">{{$message}}</span>
                @enderror
        </div>
    
    </div>
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </div>
</form>
</div>
<script>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @endpush
@endsection