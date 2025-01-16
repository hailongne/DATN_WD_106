<?php

namespace App\Http\Controllers\Admin;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\BrandsRequest;
use Illuminate\Support\Str;
use PhpParser\Builder\Use_;
use App\Models\User;
class BrandController extends Controller
{

    public function listBrand(Request $request)
    {
        $brands = Brand::where('name', 'like', '%' . $request->nhap . '%')
            ->orWhere('is_active', 'like', '%' . $request->nhap . '%')
            ->orWhere('slug', 'like', '%' . $request->nhap . '%')
            ->orWhere('description', 'like', '%' . $request->nhap . '%')
            ->latest()->paginate(5);

        return view ('admin.pages.brand.list')
        ->with(['brands'=>$brands]);
    }
    public function toggle($id)
{
    $brand = Brand::findOrFail($id);

    // Thay đổi trạng thái is_active
    $brand->is_active = !$brand->is_active;
    $brand->save();

    return redirect()->back()->with('success', 'Trạng thái thương hiệu đã được thay đổi!');
}
public function createBrand()
{

    return view('admin.pages.brand.create');
   
}
    public function addBrand(BrandsRequest $request)
    {

        Brand::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => str::slug($request->input('name')),
        ]);
        return redirect()->route('admin.brands.index')
        ->with('success', 'Thêm mới thương hiệu thành công!');

    }
    public function detailBrand($id)
    {
        $detailBrand = Brand::findOrFail($id);
        return view('admin.pages.brand.detail', compact('detailBrand'));

    }
    public function   editBrand($id)
    {
        $detailBrand = Brand::findOrFail($id);
        return view('admin.pages.brand.edit', compact('detailBrand'));

    }

    public function updateBrand(BrandsRequest $request, $id)
    {
        // Find the brand by ID
        $brand = Brand::findOrFail($id);

        // Validate the incoming request data if needed
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update the brand with the new data
        $brand->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active', 1),  // Set to 1 by default if not provided
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiẹu thành công!');
    }


    public function destroyBrand($id)
    {
        $product = Brand::findOrFail($id);
        $count = Product::where('brand_id', $id)->count();
        if($count > 0){
            return redirect()->back()->with('error' ,'Brand không thể xóa vì có sản phẩm liên quan!');
        }
        $product->delete();
        return redirect()->back()->with('success' ,'Xóa thương hiệu thành công!',);
    }


}
