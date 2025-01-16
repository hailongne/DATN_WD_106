@extends('admin.index')

@section('content')
<style>
.contact-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
}

.contact-details {
    width: 1000px;
    margin: 0px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.contact-info {
    margin-bottom: 15px;
}

.contact-info p {
    font-size: 16px;
    color: #555;
}

.contact-info strong {
    font-weight: 600;
    color: #333;
}

.contact-info span {
    font-weight: 600;
    color: #0dcaf0;
    font-style: italic;
}

.back-button {
    margin-top: 20px;
    text-align: right;
}

.back-button .btn {
    background-color: #0dcaf0;
    color: white;
    font-weight: 600;
    border-radius: 50px;
    padding: 10px 20px;
    transition: background-color 0.3s ease;
}

.back-button .btn:hover {
    background-color: #0056b3;
}
</style>
<div class="contact-details-container">
    <div class="button-header mb-3">
        <button>
            Chi Tiết Liên Hệ <i class="fa fa-star"></i>
        </button>

    </div>

    <div class="contact-details">
        <div class="contact-info">
            <p><strong>Họ và tên:</strong> <span>{{ $contact->name }}</span></p>
        </div>
        <div class="contact-info">
            <p><strong>Email:</strong> <span>{{ $contact->email }}</span></p>
        </div>
        <div class="contact-info">
            <p><strong>Số điện thoại:</strong> <span>{{ $contact->phone }}</span></p>
        </div>
        <div class="contact-info">
            <p><strong>Lời nhắn:</strong> <span>{{ $contact->message }}</span></p>
        </div>
        <div class="contact-info">
            <p><strong>Ngày gửi:</strong> <span>{{ $contact->created_at->format('d/m/Y H:i') }}</span></p>
        </div>
        <div class="back-button">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-info">Quay lại</a>
        </div>
    </div>

</div>
@endsection
