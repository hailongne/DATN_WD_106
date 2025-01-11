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
        ->where('code', 'like', '%' . $request->nhap . '%')
        ->when(request('start_date') && request('end_date'), function ($query) {
            $query->whereBetween('start_date', [request('start_date'), request('end_date')]);
        })
        ->when(request('start_date') && !request('end_date'), function ($query) {
            $query->where('start_date', '>=', request('start_date'));
        })
        ->when(request('end_date') && !request('start_date'), function ($query) {
            $query->where('start_date', '<=', request('end_date'));
        })
            ->latest()->paginate(5);
            return view('admin.pages.coupon.list',compact('coupons'));
    }
    public function toggle($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Thay đổi trạng thái is_active
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return redirect()->back()->with('success', 'Trạng thái phiếu giảm giá đã được thay đổi!');
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
    public function editCoupon(Request $request,$id){
        $coupon = Coupon::findOrFail($id);
        $users=User::where('name', 'like', '%' . $request->input('nhap') . '%')->get();

        $userCoupon = CouponUser::where('coupon_id', $id)->get();;
        return view('admin.pages.coupon.edit',
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
