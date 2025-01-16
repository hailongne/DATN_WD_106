<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function listCoupon(Request $request)
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem mã giảm giá!');
        }

        // Lấy các coupon thuộc về người dùng và chưa được sử dụng
        $coupons = Coupon::where('is_active', true)
            ->where('is_public', true) // Chỉ lấy coupon công khai
            ->whereDoesntHave('couponUsers', function ($query) use ($user) {
                $query->where('user_id', $user->user_id)
                    ->where('has_used', true); // Lọc các coupon đã sử dụng
            })
            ->latest()
            ->paginate(5);

        return view('user.coupons', compact('coupons', 'user'));
    }

}
