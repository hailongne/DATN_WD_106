<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng theo vai trò (role).
     */
    public function listUser(Request $request)
    {
        // Lọc danh sách người dùng theo role nếu có yêu cầu
        $role = $request->query('role');

        $users = User::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })->get();

        return view('admin.pages.user.listUser', compact('users', 'role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|integer',
        ]);
    
        $user = User::findOrFail($id);

        if (auth()->user()->user_id == $id) {
            return redirect()->route('admin.users.listUser')->with('error', 'Bạn không thể chỉnh sửa quyền của chính mình!');
        }

        if (auth()->user()->role >= $user->role) {
            return redirect()->route('admin.users.listUser')->with('error', 'Bạn không thể chỉnh sửa quyền của tài khoản có cùng hoặc vai trò cao hơn bạn!');
        }
    
        $user->role = $request->input('role');
        $user->save();
    
        return redirect()->route('admin.users.listUser')->with('success', 'Cập nhật vai trò thành công!');
    }
    


}

