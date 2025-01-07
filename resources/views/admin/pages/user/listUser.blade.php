@extends('admin.index')
<style>
.form-select {
    border-radius: 5px;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    font-size: 16px;
    color: #495057;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
}

.mb-4 {
    margin-bottom: 1.5rem;
    /* Tương đương với 24px */
}

.d-inline-block {
    display: inline-block;
}

.w-25 {
    width: 25%;
    /* 25% chiều rộng của bố cục cha */
}
</style>
@section('content')
<div class="container">
    <div class="button-header mb-3">
        <button>Quản lý danh sách người dùng <i class="fa fa-star"></i></button>
    </div>

    <!-- Bộ lọc vai trò -->
    <form id="filterForm" method="GET" action="{{ route('admin.users.listUser') }}" class="mb-4">
        <select name="role" id="role" class="form-select w-25 d-inline-block">
            <option value="">Tất cả</option>
            <option value="1" {{ request('role') == 1 ? 'selected' : '' }}>Admin</option>
            <option value="2" {{ request('role') == 2 ? 'selected' : '' }}>User</option>
            <option value="3" {{ request('role') == 3 ? 'selected' : '' }}>Manager</option>
        </select>
    </form>

    <!-- Bảng danh sách người dùng -->
    <table class="product-table table table-bordered text-center align-middle">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Chỉnh sửa quyền</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @switch($user->role)
                    @case(1)
                    Admin
                    @break
                    @case(2)
                    User
                    @break
                    @case(3)
                    Manager
                    @break
                    @default
                    Khác
                    @endswitch
                </td>
                <td>
                    @if (auth()->user()->role == 1)
                    <!-- Kiểm tra nếu người dùng hiện tại là Admin -->
                    <form method="POST" action="{{ route('admin.users.update-role', $user->user_id) }}"
                        style="display: inline;">
                        @csrf
                        <select name="role" class="form-select w-50 d-inline-block" onchange="this.form.submit()">
                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>User</option>
                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Manager</option>
                        </select>
                    </form>
                    @else
                    <span>Không thể chỉnh sửa</span> <!-- Nếu người dùng không phải Admin, hiển thị thông báo -->
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Không có người dùng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Script -->
<script>
document.getElementById('role').addEventListener('change', function() {
    // Tự động submit form khi thay đổi tùy chọn
    document.getElementById('filterForm').submit();
});
</script>
@endsection