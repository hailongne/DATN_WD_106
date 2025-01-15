<!-- resources/views/admin/contacts/show.blade.php -->
@extends('admin.index')

@section('content')
    <h1>Chi Tiết Liên Hệ</h1>

    <div class="contact-details">
        <p><strong>Họ và tên:</strong> {{ $contact->name }}</p>
        <p><strong>Email:</strong> {{ $contact->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
        <p><strong>Lời nhắn:</strong> {{ $contact->message }}</p>
        <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
@endsection
