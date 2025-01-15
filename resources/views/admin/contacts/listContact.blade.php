<!-- resources/views/admin/contacts/index.blade.php -->
@extends('admin.index')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/contact.css') }}">
@endpush

@section('content')
    <h1>Danh Sách Liên Hệ</h1>

    @if($contacts->isEmpty())
        <p>Không có liên hệ nào.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Ngày gửi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-info">Xem</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
