<?php

namespace App\Http\Controllers;

use App\Mail\LowStockAlert;
use App\Mail\OrderCancelled;
use App\Models\AttributeProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function orderHistory()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem lịch sử mua hàng.');
        }

        // Lấy danh sách đơn hàng của người dùng
        $orders = Order::where('user_id', auth()->id())
            ->with([
                'orderItems.product' => function ($query) {
                    $query->withTrashed();
                }
                ,
                'statusHistories'
            ]) // Eager load thêm lịch sử trạng thái và người cập nhật
            ->orderBy('order_id', 'desc') // Sắp xếp theo ngày đặt hàng mới nhất
            ->paginate(10);

        session()->flash('alert', 'Bạn đang vào trang lịch sử mua hàng');
        // Trả về view danh sách đơn hàng
        return view('user.orders.orderHistory', compact('orders'));
    }
    public function confirmDelivery($orderId)
    {
        // Tìm đơn hàng, nếu không tồn tại trả về lỗi
        $order = Order::findOrFail($orderId);

        // Kiểm tra quyền truy cập: chỉ cho phép người dùng sở hữu đơn hàng xác nhận
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('user.order.history')->with('error', 'Bạn không có quyền xác nhận đơn hàng này.');
        }

        // Kiểm tra nếu đơn hàng đã được xác nhận nhận hàng trước đó
        if ($order->received) {
            return redirect()->route('user.order.history')->with('info', 'Đơn hàng này đã được xác nhận trước đó.');
        }

        // Cập nhật trạng thái "received" cho đơn hàng
        $order->received = true;

        // Nếu đơn hàng được nhận, tự động chuyển trạng thái thành "completed" và đánh dấu thanh toán
        if ($order->status !== 'completed') {
            $order->status = 'completed';
        }
        if ($order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
        }

        $order->save();

        // Ghi lại lịch sử thay đổi trạng thái
        OrderStatusHistory::create([
            'order_id' => $order->order_id,
            'previous_status' => $order->status,
            'new_status' => 'completed',
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Gửi thông báo thành công
        session()->flash('alert', 'Đã xác nhận nhận hàng thành công!');
        return redirect()->route('user.order.history')->with('alert', 'Đơn hàng đã được xác nhận nhận hàng.');
    }


    public function show($orderId)
    {
        // Lấy thông tin đơn hàng của người dùng đang đăng nhập
        $order = Order::with([
            'orderItems.product' => function ($query) {
                $query->withTrashed();
            },
            'orderItems.color',
            'orderItems.size'
        ])->find($orderId);
        session()->flash('alert', 'Bạn đang vào trang chi tiết đơn hàng');
        return view('user.orders.detail', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = ShoppingCart::where('user_id', auth()->id())->with('cartItems')->firstOrFail();

        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $cart->cartItems->sum(function ($item) {
                return $item->qty * $item->price;
            }),
            'status' => 'pending', // Trạng thái mặc định
        ]);

        // Lưu từng sản phẩm trong giỏ hàng vào chi tiết đơn hàng
        foreach ($cart->cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->price,
            ]);
        }

        // Xóa giỏ hàng sau khi đặt hàng
        $cart->cartItems()->where('shopping_cart_id', $cart->id)->delete();
        $cart->delete();
        session()->flash('alert', 'Đặt hàng thành công!');
        return redirect()->route('user.orders.index')->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with('payment')->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        session()->flash('alert', 'Đặt hàng thành công!');
        return view('user.orders.order-cod', compact('order'));
    }
    public function cancelOrder($orderId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để hủy đơn hàng.');
        }

        $order = Order::where('order_id', $orderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return redirect()->route('user.order.history')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('user.order.history')->with('error', 'Đơn hàng không thể hủy ở trạng thái này.');
        }
        foreach ($order->orderItems as $orderItem) {
            $product = $orderItem->product;

            if ($product && $product->attributeProducts) {
                $attributeProduct = $product->attributeProducts
                    ->where('size_id', $orderItem->size_id)
                    ->first();

                if ($attributeProduct) {
                    // Cộng lại số lượng vào in_stock
                    $attributeProduct->in_stock += $orderItem->quantity; // Đổi `qty` thành `quantity`
                    $attributeProduct->save();
                }
            }
        }
        // Lưu lịch sử thay đổi trạng thái
        OrderStatusHistory::create([
            'order_id' => $order->order_id,
            'previous_status' => $order->status,
            'new_status' => 'cancelled',
            'updated_by' => auth()->id(),
        ]);

        // Cập nhật trạng thái đơn hàng
        $order->update(['status' => 'cancelled', 'payment_status'=> 'failed']);

        Mail::to($order->user->email)->send(new OrderCancelled($order));

        session()->flash('alert_2', 'Đơn hàng đã được hủy thành công.');
        return redirect()->route('user.order.history');
    }
    public function confirmOrder(Request $request)
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }
    
        // Lấy giỏ hàng của người dùng
        $shoppingCart = ShoppingCart::where('user_id', $user->user_id)->first();
        if (!$shoppingCart) {
            return redirect()->route('shopping-cart')->with('error', 'Giỏ hàng trống!');
        }
    
        // Lấy các ID sản phẩm đã được chọn từ request
        $selectedProductIds = explode(',', $request->input('selected_products'));
    
        // Lọc các items trong giỏ hàng dựa trên các ID đã được chọn
        $cartItems = $shoppingCart->cartItems->filter(function ($item) use ($selectedProductIds) {
            return in_array($item->id, $selectedProductIds);
        });
    
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào được chọn để thanh toán!');
        }
     
        
        // Tính tổng tiền đơn hàng (không bao gồm phí vận chuyển)
        $totalWithoutShipping = 0;
        $productDetails = []; // Lưu thông tin chi tiết sản phẩm
        
        foreach ($cartItems as $item) {
            // Lấy thông tin sản phẩm với size_id và color_id
            $attributeProduct = $item->product->attributeProducts
                ->where('size_id', $item->size_id)
                ->where('color_id', $item->color_id)
                ->first();
    
            if ($attributeProduct) {
                // Kiểm tra số lượng sản phẩm trong kho
                if ($attributeProduct->in_stock < $item->qty) {
                    session()->flash('error', 'Một số sản phẩm vừa được cập nhật thông tin, vui lòng kiểm tra lại!');
                    return redirect()->back();
                }
    
                // Tính tổng tiền
                $totalWithoutShipping += $attributeProduct->price * $item->qty;
    
                // Lưu thông tin chi tiết sản phẩm
                $productDetails[] = [
                    'name' => $item->product->name,
                    'product_id' => $item->product->product_id,
                    'img' => $item->product->main_image_url,
                    'color' => $item->color->name,  // Lấy tên màu từ quan hệ color
                    'size' => $item->size->name,    // Lấy tên size từ quan hệ size
                    'quantity' => $item->qty,
                    'price' => $attributeProduct->price,
                    'subtotal' => $attributeProduct->price * $item->qty,
                    'color_id' => $item->color_id,  // Lưu color_id
                    'size_id' => $item->size_id    // Lưu size_id
                ];
    
                // Cập nhật số lượng tồn kho sau khi thanh toán
                $attributeProduct->update([
                    'in_stock' => $attributeProduct->in_stock - $item->qty,
                ]);
            //cập nhật view trong productView
            foreach ($cartItems as $item) {
                $productId = $item->product->product_id;
    
                if ($productId) {
                    // Kiểm tra xem sản phẩm có tồn tại trong ProductView không
                    $productView = ProductView::where('product_id', $productId)
                                              ->where('user_id', Auth::id())
                                              ->first();
    
                    if ($productView) {
                        // Nếu tồn tại, xóa bản ghi
                        $productView->delete();

            }
        }
    
        // Thêm phí vận chuyển
        $shippingFee = 40000;
        $total = $totalWithoutShipping + $shippingFee;
        session()->put('productDetails', $productDetails);
    
        return view('user.orders.orderConfirm', [
            'user' => $user,
            'productDetails' => $productDetails,
            'totalWithoutShipping' => $totalWithoutShipping,
            'total' => $total,
            'shippingFee' => $shippingFee
        ]);
    }
    

    public function confirmOrderVNPay(Request $request)
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        // Lấy giỏ hàng của người dùng
        $shoppingCart = ShoppingCart::where('user_id', $user->user_id)->first();
        if (!$shoppingCart) {
            return redirect()->route('shopping-cart')->with('error', 'Giỏ hàng trống!');
        }

        $cartItems = $shoppingCart->cartItems;

        // Tính tổng tiền đơn hàng (không bao gồm phí vận chuyển)
        $totalWithoutShipping = 0;
        $productDetails = []; // Lưu thông tin chi tiết sản phẩm
        foreach ($cartItems as $item) {
            // Lấy thông tin sản phẩm với size_id và color_id
            $attributeProduct = $item->product->attributeProducts->firstWhere('size_id', $item->size_id);
            if ($attributeProduct) {
                $totalWithoutShipping += $attributeProduct->price * $item->qty;

                $productDetails[] = [
                    'name' => $item->product->name,
                    'color' => $item->color->name,  // Lấy tên màu từ quan hệ color
                    'size' => $item->size->name,    // Lấy tên size từ quan hệ size
                    'quantity' => $item->qty,
                    'price' => $attributeProduct->price,
                    'subtotal' => $attributeProduct->price * $item->qty,
                    'color_id' => $item->color_id,  // Lưu color_id
                    'size_id' => $item->size_id    // Lưu size_id
                ];
            }
        }
        if ($attributeProduct->in_stock < $item->qty) {
            return redirect()->route('shopping-cart')->with('error', 'Sản phẩm "' . $item->product->name . '" không đủ số lượng trong kho!');
        }

        $attributeProduct->update([
            'in_stock' => $attributeProduct->in_stock - $item->qty,
        ]);
        $attributeProducts = AttributeProduct::where('in_stock', '<=', 'warning_threshold')->get();
        foreach ($attributeProducts as $attributeProduct) {
            $this->sendLowStockEmail($attributeProduct);
        }
        // Thêm phí vận chuyển
        $shippingFee = 40000;
        $total = $totalWithoutShipping + $shippingFee;

        // Chuyển hướng đến trang thông báo thanh toán thành công và truyền thông tin
        return view('user.orders.orderConfirmVNPay', [
            'user' => $user,
            'productDetails' => $productDetails,
            'totalWithoutShipping' => $totalWithoutShipping,
            'total' => $total,
            'shippingFee' => $shippingFee
        ]);

    }
    public function sendLowStockEmail($attributeProduct)
    {
        // Địa chỉ email của quản trị viên
        $adminEmail = 'longnhph44749@fpt.edu.vn'; // Địa chỉ email của quản trị viên

        // Gửi email cảnh báo
        Mail::to($adminEmail)->send(new LowStockAlert($attributeProduct));
    }
}
