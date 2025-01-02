<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeProduct;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function Stats(Request $request)
    {
        // Lấy ngày bắt đầu và ngày kết thúc từ form (Doanh thu và Đơn hàng đều dùng chung)
        $startDate = $request->input('start_date', Carbon::now()->subDays(6)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
    
        // Thống kê doanh thu theo ngày trong khoảng thời gian được chọn
        $dailyStats = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        $dailyLabels = $dailyStats->pluck('date');
        $dailyOrders = $dailyStats->pluck('total_orders');
        $dailyRevenue = $dailyStats->pluck('revenue');
    
        // Thống kê đơn hàng theo ngày trong khoảng thời gian được chọn
        $ordersStats = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        $ordersLabels = $ordersStats->pluck('date');
        $ordersTotal = $ordersStats->pluck('total_orders');
    
        // Thống kê tổng sản phẩm theo danh mục
        $categories = Category::withCount('products')->get();
    
        $inventoryStats = Product::with(['attributeProducts.color', 'attributeProducts.size'])
            ->select('product_id', 'name')
            ->get();
    
        $soldProductsStats = Product::select('products.product_id', 'products.name', DB::raw('SUM(order_items.quantity) as sold_quantity'))
            ->join('order_items', 'order_items.product_id', '=', 'products.product_id')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate]) // Lọc theo khoảng thời gian
            ->groupBy('products.product_id', 'products.name')
            ->get();
    
        // Trả dữ liệu về view
        return view('admin.dashboard', compact(
            'dailyLabels',
            'dailyOrders',
            'dailyRevenue',
            'ordersLabels',
            'ordersTotal',
            'startDate',
            'endDate',
            'categories',
            'soldProductsStats',
            'inventoryStats'
        ));
    }
    
}
