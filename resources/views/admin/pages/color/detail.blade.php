@extends('admin.index')
@section('content')
<style>
.table td.color-code {
    color: #fff;
    font-weight: bold;
}
</style>
<style>
/* Basic container styling */
.container-detail-custom {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

/* Card/Details styling */
.details-card-custom {
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: left;
}

/* Styling for brand name and details */
.brand-info-custom h3 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.brand-info-custom p {
    font-size: 1rem;
    color: #555;
    margin: 5px 0;
}

.brand-info-custom span {
    font-weight: normal;
}

/* Button Styling */
.btn-custom {
    padding: 10px 20px;
    font-size: 1rem;
    text-transform: uppercase;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.text-center-custom {
    text-align: center;
}

.mt-4-custom {
    margin-top: 1.5rem;
}

/* Color code styling */
.color-box-custom {
    width: 50px;
    height: 50px;
    display: inline-block;
    border-radius: 5px;
    margin-right: 10px;
}
</style>
<div class="container mt-5 ">
<div class="container-detail-custom">
    <div class="details-card-custom">
        <div class="brand-info-custom">
            <h3 class="brand-name-custom">
                Tên Thương Hiệu:
                <span id="name">{{ $color->name }}</span>
            </h3>
            <p class="color-code-custom">
                <strong>Mã màu:</strong>
                <span id="description">{{ $color->color_code }}</span>
            <div class="color-box-custom" style="background-color: {{ $color->color_code }}"></div>
            </p>
            <p class="created-at-custom">
                <strong>Ngày Tạo:</strong>
                <span id="created_at">{{ \Carbon\Carbon::parse($color->created_at)->format('d/m/Y H:i') }}</span>
            </p>
            <p class="updated-at-custom">
                <strong>Ngày Cập Nhật:</strong>
                <span id="updated_at">{{ \Carbon\Carbon::parse($color->updated_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    @push('scripts')
    @endpush

    @endsection