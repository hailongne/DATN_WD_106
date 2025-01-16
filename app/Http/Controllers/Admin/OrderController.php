<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    //
    public function home()
    {
        return view('admin.index');
    }
    // public function index()
    // {
    //     return view('admin.pages.order_management');
    //     $orders = Order::with('orderItems')->get(); // Lấy tất cả đơn hàng cùng các item
    //     return response()->json($orders);
    // }

    // Lấy chi tiết một đơn hàng
    public function showAllOrders(Request $request)
    {
        $startDate = $request->input('start_date'); // Ngày bắt đầu
        $endDate = $request->input('end_date');     // Ngày kết thúc
        $status = $request->input('status');        // Trạng thái đơn hàng
    
        $query = Order::with('user');
    
        // Lọc theo ngày bắt đầu và ngày kết thúc
        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate));
        }
    
        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }
    
        // Sắp xếp và lấy dữ liệu
        $orders = $query->orderBy('order_id', 'desc')->get();
    
        return view('admin.pages.order.order_management', compact('orders'));
    }

    public function showDetailOrder($orderId)
{
    $order = Order::with(['orderItems.attributeProduct.product'])->findOrFail($orderId);

    return view('admin.pages.order.orderDetail', compact('order'));
}
    //Cập nhật
    // AdminController.php

    public function updateOrderStatus(Request $request)
    {
        // Kiểm tra nếu đơn hàng tồn tại
        $order = Order::find($request->order_id);
    
        if ($order) {
            $previousStatus = $order->status; // Lưu trạng thái cũ trước khi cập nhật
            // Cập nhật trạng thái đơn hàng
            $order->status = $request->status;
            $order->save();
    
            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
    
                if ($product && $product->attributeProducts) {
                    $attributeProduct = $product->attributeProducts
                        ->where('size_id', $orderItem->size_id)
                        ->first();
    
                    if ($attributeProduct) {
                        // Cộng lại số lượng vào in_stock
                        $attributeProduct->in_stock += $orderItem->quantity;
                        $attributeProduct->save();
                    }
                }
            }
    
            // Ghi lại lịch sử thay đổi trạng thái
            OrderStatusHistory::create([
                'order_id' => $order->order_id,
                'previous_status' => $previousStatus,
                'new_status' => $request->status,
                'updated_by' => auth()->id(),
            ]);
    
            // Gửi email thông báo cập nhật trạng thái
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $previousStatus, $request->status));
    
            // Trả về phản hồi JSON thành công
            return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công và đã gửi email!']);
        }
    
        // Nếu không tìm thấy đơn hàng
        return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng!']);
    }
    
    
}