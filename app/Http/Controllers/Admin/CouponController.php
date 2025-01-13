<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\CouponRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\CouponCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CouponProduct;
use App\Models\CouponUser;
class CouponController extends Controller
{
    //
    public function listCoupon(Request $request)
    {
        $coupons = Coupon::with('users')
    ->where(function($query) use ($request) {
        $query->where('code', 'like', '%' . $request->nhap . '%')
              ->orWhere('quantity', 'like', '%' . $request->nhap . '%')
              ->orWhere('min_order_value', 'like', '%' . $request->nhap . '%')
              ->orWhere('max_order_value', 'like', '%' . $request->nhap . '%');
    })
    ->when(request('start_date') && request('end_date'), function ($query) {
        // Lọc theo cả start_date và end_date
        $query->whereBetween('start_date', [request('start_date'), request('end_date')]);
    })
    ->when(request('start_date') && !request('end_date'), function ($query) {
        // Lọc theo start_date mà không cần end_date
        $query->where('start_date', '>=', request('start_date'));
    })
    ->when(!request('start_date') && request('end_date'), function ($query) {
        // Lọc theo end_date mà không cần start_date
        $query->where('end_date', '<=', request('end_date'));
    })
    ->latest()
    ->paginate(5);

return view('admin.pages.coupon.list', compact('coupons'));

    
    
    
    }
    public function toggle($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Thay đổi trạng thái is_active
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return redirect()->back()->with('success', 'Trạng thái phiếu giảm giá đã được thay đổi!');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $authUserId = auth()->id(); // ID của user hiện tại
    
        $users = User::where('name', 'like', '%' . $query . '%')
            ->where('user_id', '!=', $authUserId) // Loại bỏ user đang đăng nhập
            ->where('role', '!=', 1) // Loại bỏ user có role = 1 (admin)
            ->get();
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
    
    public function createCoupon(Request $request){
        
        $users=User::where('name', 'like', '%' . $request->input('nhap') . '%')->get();
        return view('admin.pages.coupon.create',compact('users'));

}
    public function addCoupon(CouponRequest $request)
    {
        $coupon = Coupon::create([
            'code' => $request->input('code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'quantity' => $request->input('quantity'),
            'min_order_value' => $request->input('min_order_value'),
            'max_order_value' => $request->input('max_order_value'),
            'condition' => $request->input('condition'),
            'is_public' => $request->input('is_public', true),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
        $couponUsers = [];
        // $couponProducts = [];
        if ($request->has('user_id')) {
            foreach ($request->input('user_id') as $userId) {
                $couponUser = CouponUser::create([
                    'coupon_id' => $coupon->coupon_id,
                    'user_id' => $userId,
                ]);
                $couponUsers[] = $couponUser;
                   // Gửi email thông báo sau khi tạo coupon liên kết với người dùng
            Mail::to(User::find($userId)->email)->send(new CouponCreated($coupon));
            }
        }
    

        return redirect()->route('admin.coupons.index')->with([
            'coupon' => $coupon,
            'couponUsers' => $couponUsers,
            'success' => 'Thêm mới phiếu giảm giá thành công!',
        ], 201);

    }
    public function detailCoupon($id){
        $coupon = Coupon::findOrFail($id);
        return view('admin.pages.coupon.detail',compact('coupon'));
    }
    // UserController.php
    public function searchId(Request $request, $id)
{
    $authUserId = auth()->id(); // ID của user hiện tại
    $coupon = Coupon::findOrFail($id);
    $query = $request->input('query');
    // Lấy danh sách tất cả người dùng
    $users = User::where('name', 'like', '%' . $query . '%')->where('user_id', '!=', $authUserId) // Loại bỏ user đang đăng nhập
    ->where('role', '!=', 1)->get(); // Loại bỏ user có role = 1 (admin);

    // Lấy danh sách user_id đã sử dụng coupon này
    $userCouponIds = CouponUser::where('coupon_id', $id)->pluck('user_id')->toArray(); // Mảng ID người dùng đã dùng coupon

    // Trả về dữ liệu dưới dạng JSON
    return response()->json([
        'users' => $users,
        'userCouponIds' => $userCouponIds,
    ]);
}
    public function editCoupon(Request $request,$id){
        $coupon = Coupon::findOrFail($id);
        $users=User::where('name', 'like', '%' . $request->input('nhap') . '%')->get();

        $userCoupon = CouponUser::where('coupon_id', $id)->get();

        return view('admin.pages.coupon.edit',[  'couponId' => $id], // Truyền couponId vào view],
        compact('coupon','userCoupon','users'));
    }
    public function updateCoupon(CouponRequest $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        // Update coupon details
        $coupon->update([
            'code' => $request->input('code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'quantity' => $request->input('quantity'),
            'min_order_value' => $request->input('min_order_value'),
            'max_order_value' => $request->input('max_order_value'),
            'condition' => $request->input('condition'),
            'is_public' => $request->input('is_public'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        $couponUsers = [];


        if ($request->has('user_id')) {

            CouponUser::where('coupon_id', $id)->delete();

            foreach ($request->input('user_id') as $userId) {
                $couponUser = CouponUser::create([
                    'coupon_id' => $id,
                    'user_id' => $userId,
                ]);
                $couponUsers[] = $couponUser;
            }
        }

        return redirect()->route('admin.coupons.index')->with([
            'coupon' => $coupon,
            'couponUsers' => $couponUsers,
            'message' => 'Cập nhật phiếu giảm giá thành công!',
        ], 200);
    }
    public function destroyCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        if (!$coupon) {
            // Nếu mã giảm giá không tồn tại
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại.');
        }
        $hasUsers = CouponUser::where('coupon_id', $id)->exists();

    if ($hasUsers) {
        // Nếu mã giảm giá đã được sử dụng
        return redirect()->back()->with('error', 'Không thể xóa mã giảm giá đã được sử dụng bởi người dùng.');
    }
    // Xóa mã giảm giá
    try {
        $coupon->delete();
        return redirect()->back()->with('success', 'Xóa mã giảm giá thành công.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa mã giảm giá.');
    }
       
    }

}
