<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $listProduct = Product::with([
            'attributeProducts',
            'promPerProducts.promPer' => function ($query) {
                $query->where('is_active', 1);// 
            }
        ])
        ->where('is_active', 1) // Lọc sản phẩm đang hoạt động
        ->get();
        $topProducts = Product::select('products.*', DB::raw('SUM(product_views.view_count) as total_views'))
        ->join('product_views', 'products.product_id', '=', 'product_views.product_id')
        ->where('product_views.user_id', Auth::id())  // Lọc theo người dùng hiện tại
        ->groupBy('products.product_id', 'products.brand_id', 'products.product_category_id', 
        'products.name','products.main_image_url','products.view_count','.products.description','products.sku','products.subtitle',
        'products.slug','products.is_active','products.deleted_at','products.created_at'
        ,'products.updated_at','products.is_hot','products.is_best_seller')  // Nhóm theo sản phẩm
        ->orderByDesc('total_views')  // Sắp xếp theo tổng lượt xem giảm dần
        ->take(4)  // Lấy 4 sản phẩm có lượt xem cao nhất
        ->get();
    
      
        
    
    
    
        // Eager load cả 'attributeProducts' từ bảng attribute_products
        // $listProduct = Product::with('attributeProducts')
        //     ->where('is_active', 1)  // Lọc sản phẩm có trạng thái is_active = 1 (sản phẩm đang hoạt động)
        //     ->get();
        $bestSellers = Product::getBestSellers();
        $hotProducts = Product::getHotProducts();
        return view('user.home', 
        compact('listProduct', 'hotProducts', 'bestSellers','topProducts'));
    }
}
