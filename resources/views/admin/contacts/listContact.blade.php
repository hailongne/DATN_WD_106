<!-- resources/views/admin/contacts/index.blade.php -->
@extends('admin.index')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/contact.css') }}">
@endpush

@section('content')
<div class="button-header mb-3">
    <button>
        Danh Sách Liên Hệ <i class="fa fa-star"></i>
    </button>
</div>

@if($contacts->isEmpty())
<p>Không có liên hệ nào.</p>
@else
<table class="product-table table table-bordered text-center align-middle">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Họ và tên</th>
            <th>ID</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Ngày gửi</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($contacts as $contact)
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $contact->name }}</td>
            <td>{{ $contact->id }}</td>
            <td>{{ $contact->email }}</td>
            <td>{{ $contact->phone }}</td>
            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <div class="icon-product d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="text-info">
                        <button class="action-btn eye" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </button>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
