<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirm;
use App\Models\OrderStatusHistory;
use App\Models\UsedCoupon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AttributeProduct;

class PaymentController extends Controller
{
    //
    public function checkoutCOD(Request $request)
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        // Kiểm tra xem người dùng đã chọn sản phẩm chưa
        $productDetails = session()->get('productDetails', []);
        if (empty($productDetails)) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào để thanh toán!');
        }

        // Lấy thông tin địa chỉ giao hàng và số điện thoại từ form
        $amount = $request->input('amount');
        $shippingAddress = $request->input('shipping_address');
        $phone = $request->input('phone');
        $recipients_name = $request->input('recipient_name');

        // Tính tổng tiền đơn hàng (không bao gồm phí vận chuyển)
        $totalWithoutShipping = 0;
        foreach ($productDetails as $product) {
            $totalWithoutShipping += $product['price'] * $product['quantity'];
        }
        $discountAmount = 0;
        $discountCode = $request->input('discount_code'); // Lấy mã giảm giá từ form
        $coupon = Coupon::where('code', $discountCode)->first();
        if ($discountCode) {
            $coupon = Coupon::where('code', $discountCode)->first();
            if ($coupon && $coupon->is_active && $coupon->is_public) {
                // Tính toán giảm giá sau khi đã cộng phí vận chuyển
                $shippingFee = 40000; // Phí vận chuyển
                $totalAfterShipping = $totalWithoutShipping + $shippingFee; // Tổng tiền sau khi cộng phí vận chuyển
                $discountAmount = $this->calculateDiscount($coupon, $totalAfterShipping);
            }
        }

        // Thêm phí vận chuyển
        $shippingFee = 40000;
        $total = $request->input('amount');

        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => $user->user_id,
            'shipping_address' => $shippingAddress,
            'phone' => $phone,
            'total' => $total,
            'invoice_date' => now(),
            'shipping_fee' => $shippingFee,
            'status' => 'pending',
            'payment_method' => 'COD',
            'recipient_name' => $recipients_name
        ]);

        // Thêm các sản phẩm vào đơn hàng
        foreach ($productDetails as $product) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product['product_id'],
                'product_name' => $product['name'],
                'color_id' => $product['color_id'],
                'size_id' => $product['size_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['subtotal'],
            ]);
        }
        OrderStatusHistory::create([
            'order_id' => $order->order_id,
            'new_status' => 'pending',
            'status_change_date' => now(),
            'updated_by' => $user->user_id
        ]);

        //Gửi email xác nhận đơn hàng
        $emailData = [
            'user' => $user,
            'address' => $shippingAddress,
            'phone' => $phone,
            'productDetails' => $productDetails,
            'total' => $total,
            'shippingFee' => $shippingFee
        ];
        
        Mail::to($user->email)->send(new OrderConfirm($emailData));

        // Chuyển hướng đến trang thông báo thanh toán thành công
        return redirect()
            ->route('user.order.order-cod')
            ->with('alert', 'Đơn hàng của bạn đã được thanh toán thành công. Cảm ơn bạn!')
            ->with(['discountAmount' => $discountAmount]);
    }



    private function calculateDiscount($coupon, $total)
    {
        // Kiểm tra kiểu giảm giá (theo số tiền hay phần trăm)
        if ($coupon->discount_percentage) {
            $discount = ($total * $coupon->discount_percentage) / 100;
            // Đảm bảo giảm giá không vượt quá giá trị tối đa
            return min($discount, $coupon->max_order_value);
        } elseif ($coupon->discount_amount) {
            return $coupon->discount_amount;
        }
        return 0;
    }

    public function applyDiscount(Request $request)
    {
        $code = $request->input('discount_code');
        $amount = $request->input('amount');
        $discountAmount = 0;
        $userEmail = auth()->user()->email; 
        $userId = auth()->id();       
        // Tìm mã giảm giá  và kiểm tra tất cả các điều kiện
        $coupon = Coupon::where('code', $code)
            ->where('is_active', '1')
            ->where('is_public', '0')   
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
            $coupons = Coupon::whereHas('couponUsers', function ($query) use ($userEmail) {
                $query->where('email', $userEmail);  // Kiểm tra email người dùng có trong danh sách couponUsers
            })
            ->where('code', $code)
            ->where('is_active', '1')
            ->where('is_public', '1')  
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now()) 
            ->first();

      if(isset($coupon)) {

        $hasUsed = UsedCoupon::where('user_id', $userId)
        ->where('coupon_id', $coupon->coupon_id)
        ->exists();

    if ($hasUsed) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn đã sử dụng mã giảm giá này rồi.',
        ]);
    }
        // nếu hết mã giảm giá
        if ($coupon->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá này đã được sử dụng hết.',
            ]);
        }

        // Kiểm tra giá trị đơn hàng có hợp lệ với mã giảm giá không
        if ($amount < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá chỉ áp dụng cho đơn hàng có giá trị từ ' . 
        number_format($coupon->min_order_value, 0, ',', '.') . ' VND trở lên.',
            ]);
        }

        // Tính giá trị giảm giá
        if ( isset($coupon->discount_amount) && $coupon->discount_amount > 0) {
        // Nếu có giá trị giảm giá cố định
            $discountAmount = $coupon->discount_amount;
        } else {
            // Nếu không, tính giảm giá theo tỷ lệ phần trăm
            $discountAmount = $amount * $coupon->discount_percentage / 100;
              if ($discountAmount > $coupon->max_order_value) {
        $discountAmount = $coupon->max_order_value;
    }
        }
        if ($discountAmount > $amount) {
            $discountAmount = $amount;
        }
        UsedCoupon::insert([
            'user_id' => $userId,
            'coupon_id' => $coupon->coupon_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
      }
      elseif(isset($coupons)){

        $hasUsed = UsedCoupon::where('user_id', $userId)
        ->where('coupon_id', $coupons->coupon_id)
        ->exists();

    if ($hasUsed) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn đã sử dụng mã giảm giá này rồi.',
        ]);
    }

    if ($coupons->quantity <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá này đã được sử dụng hết.',
        ]);
    }
    if ($amount < $coupons->min_order_value) {
        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá chỉ áp dụng cho đơn hàng có giá trị từ ' . 
    number_format($coupons->min_order_value, 0, ',', '.') . ' VND trở lên.',
        ]);
    }
    $couponUser = $coupons->couponUsers->where('email', $userEmail)->first();
        // Kiểm tra quyền sử dụng mã giảm giá cho người dùng
        if (!isset( $couponUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá này không dành cho bạn.',
            ]);
        }
     // Tính giá trị giảm giá
     if ( isset($coupons->discount_amount) && $coupons->discount_amount > 0) {
        // Nếu có giá trị giảm giá cố định
            $discountAmount = $coupons->discount_amount;
        } else {
    $discountAmount = $amount * $coupons->discount_percentage / 100;

    if ($discountAmount > $coupons->max_order_value) {
        $discountAmount = $coupons->max_order_value;
    }
        }
        if ($discountAmount > $amount) {
            $discountAmount = $amount;
        }

        UsedCoupon::insert([
            'user_id' => $userId,
            'coupon_id' => $coupons->coupon_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

      }else{
        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
        ]);
      }
      
      -
     
        session(['discount_code' => $code]);
        // $coupon->decrement('quantity');
        // Cập nhật số lượng mã giảm giá nếu có
        // if ($coupon->quantity > 0) {
        //     $coupon->decrement('quantity');
        // }

        // Tính tổng mới sau khi áp dụng mã giảm giá
        $shippingFee = 40000;
        $newTotal = $amount - $discountAmount +  $shippingFee;


        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được áp dụng thành công!',
            'discount' => $discountAmount,
            'newTotal' => $newTotal,
        ]);
    }



    public function orderSuccess()
    {
        $user = Auth::user();
        // Lấy thông tin giỏ hàng của người dùng
        $shoppingCart = ShoppingCart::where('user_id', $user->user_id)->first();
        if ($shoppingCart) {
            // Chuyển thông tin sản phẩm từ session thành collection để dễ thao tác
            $productDetails = collect(session()->get('productDetails', []));

            // Duyệt qua từng sản phẩm trong giỏ hàng và kiểm tra sự trùng lặp với sản phẩm trong session
            foreach ($shoppingCart->cartItems as $cartItem) {
                $attributeProduct = AttributeProduct::where('product_id', $cartItem->product_id)
                    ->where('size_id', $cartItem->size_id)
                    ->where('color_id', $cartItem->color_id)
                    ->first();

                if ($attributeProduct) {
                    // Giảm số lượng tồn kho theo số lượng sản phẩm trong giỏ hàng
                    $attributeProduct->update([
                        'in_stock' => $attributeProduct->in_stock - $cartItem->qty,
                    ]);

                    // Đảm bảo số lượng tồn kho không âm
                    if ($attributeProduct->in_stock < 0) {
                        $attributeProduct->in_stock = 0;
                    }

                    // Lưu thay đổi vào cơ sở dữ liệu
                    $attributeProduct->save();
                }
                // Tìm kiếm sản phẩm trong session có trùng `product_id`, `size_id`, `color_id` với sản phẩm trong giỏ hàng
                $matchingProduct = $productDetails->first(function ($product) use ($cartItem) {
                    return $product['product_id'] == $cartItem->product_id
                        && $product['size_id'] == $cartItem->size_id
                        && $product['color_id'] == $cartItem->color_id;
                });

                // Nếu tìm thấy sản phẩm trùng khớp trong session, xóa sản phẩm đó khỏi giỏ hàng
                if ($matchingProduct) {

                    $cartItem->delete();
                    // Xóa sản phẩm trùng khớp trong giỏ hàng
                }
                
            }
        }
        $discountCode = session('discount_code');
        if ($discountCode) {
            $coupon = Coupon::where('code', $discountCode)->first();
            if ($coupon && $coupon->quantity > 0) {
                $coupon->decrement('quantity');  // Giảm số lượng mã giảm giá
            }
        }
        
        // Lấy thông tin tên người dùng từ session
        $userName = session('userName');
        $successMessage = session('success');

        // Trả về view với thông báo và tên người dùng
        return view('user.orders.order-cod', compact('userName', 'successMessage'))->with('alert', 'Đơn hàng của bạn đã được thanh toán thành công. Cảm ơn bạn!');
    }
}
