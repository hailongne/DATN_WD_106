<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LowStockAlert;
use App\Models\AttributeProduct;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    public function listProduct(Request $request)
    {
        $products = Product::with('category:category_id,name', 'brand:brand_id,name')
            ->when($request->input('nhap'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('nhap') . '%');
            })
            ->when($request->input('filter'), function ($query) use ($request) {
                $query->orWhereHas('category', function ($q) use ($request) {
                    $q->where('category_id', 'like', '%' . $request->input('filter') . '%');
                });
            })
            ->when($request->input('brand'), function ($query) use ($request) {
                $query->orWhereHas('brand', function ($q) use ($request) {
                    $q->where('brand_id', 'like', '%' . $request->input('brand') . '%');
                });
            })

            ->latest()->paginate(5);
        return view('admin.pages.inventory.list')
            ->with(['products' => $products]);

    }
    public function detailProduct($id)
    {
        $attPros = AttributeProduct::with([
            'product:product_id,name,sku,is_best_seller,is_hot,is_active',
            'color:color_id,name',
            'size:size_id,name'
        ])
            ->where('product_id', $id)

            ->get();

        return view('admin.pages.inventory.detail', compact('attPros'));
    }
    
    public function editQuantity($id)
    {
        $attPro = AttributeProduct::findOrFail($id);   
        return view('admin.pages.inventory.edit', compact('attPro'));
    }
    public function updateStock(Request $request, $id)
{
    $attributeProduct = AttributeProduct::findOrFail($id);

    // Cập nhật số lượng tồn kho
    $attributeProduct->update([
        'in_stock' => $request->input('in_stock'),
    ]);

    // Kiểm tra nếu tồn kho nhỏ hơn hoặc bằng ngưỡng cảnh báo
    $attributeProducts = AttributeProduct::where('in_stock', '<=', 'warning_threshold')->get();
    foreach ($attributeProducts as $attributeProduct) {
        $this->sendLowStockEmail($attributeProduct);
    }

    return back()->with('success', 'Cập nhật tồn kho thành công!');
}
public function sendLowStockEmail($attributeProduct)
{
    // Địa chỉ email của quản trị viên
    $adminEmail = 'longxahoi7@gmail.com'; // Địa chỉ email của quản trị viên

    // Gửi email cảnh báo
    Mail::to($adminEmail)->send(new LowStockAlert($attributeProduct));
}
}
