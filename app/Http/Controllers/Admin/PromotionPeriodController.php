<?php

namespace App\Http\Controllers\Admin;
use App\Models\Coupon;
use App\Http\Controllers\Controller;
use App\Models\CouponProduct;
use App\Models\Product;
use App\Models\PromPer;
use Illuminate\Http\Request;
use App\Models\PromPerProduct;

class PromotionPeriodController extends Controller
{
    //
    public function listPromotionPeriod(Request $request)
    {
        $promPers = PromPer::with( 'products')->where('code', 'like', '%' . $request->nhap . '%')
            ->orWhere('is_active', 'like', '%' . $request->nhap . '%')
            ->orWhere('discount_percentage', 'like', '%' . $request->nhap . '%')
            ->orWhere('discount_amount', 'like', '%' . $request->nhap . '%')
            ->orWhere('start_date', 'like', '%' . $request->nhap . '%')
            ->orWhere('end_date', 'like', '%' . $request->nhap . '%')
            ->latest()->paginate(5);
        return view('admin.pages.promPer.list', compact('promPers'));
    }
    public function toggle($id)
    {
        $promPer = PromPer::findOrFail($id);

        // Thay đổi trạng thái is_active
        $promPer->is_active = !$promPer->is_active;
        $promPer->save();

        return redirect()->back()->with('success', 'Trạng thái phiếu giảm giá đã được thay đổi!');
    }
    public function createPromotionPeriod(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->nhap . '%')->get();
        return view('admin.pages.promPer.create', compact('products'));
    }
    public function addPromotionPeriod(Request $request)
    {
        $promPer = PromPer::create([
            'code' => $request->input('code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        $promPerProducts = [];

        if ($request->has('product_id')) {
            foreach ($request->input('product_id') as $productId) {
                $promPerPro = PromPerProduct::create([
                    'prom_per_id' => $promPer->prom_per_id,
                    'product_id' => $productId
                ]);
                $promPerProducts[] = $promPerPro;
            }
        }
        return redirect()->route('admin.promotionPeriods.index')->with([

            'promPerProducts' => $promPerProducts,
            'message' => 'Coupon added successfully!',
        ], 201);

    }
    public function detailPromotionPeriod($id){
        $promPer = PromPer::findOrFail($id);
        return view('admin.pages.promPer.detail',compact('promPer'));
    }
    public function editPromotionPeriod(Request $request,$id){
        $promPer = PromPer::findOrFail($id);
        $products=Product::where('name', 'like', '%' . $request->nhap . '%')->get();

        $proProm =PromPerProduct::where('prom_per_id', $id)->get();;
        return view('admin.pages.promPer.edit',
        compact('promPer','proProm','products'));
    }

    public function updatePromotionPeriod(Request $request, $id)
    {
        $promPer = PromPer::findOrFail($id);
        // Update promPer details
        $promPer->update([
            'code' => $request->input('code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);


        $promPerProducts = [];


        if ($request->has('product_id')) {
            // Remove old associations
            PromPerProduct::where('prom_per_id', $id)->delete();

            // Add new associations and collect updated data
            foreach ($request->input('product_id') as $productId) {
                $promPerPro = PromPerProduct::create([
                    'prom_per_id' => $id,
                    'product_id' => $productId,
                ]);
                $promPerProducts[] = $promPerPro;
            }
        }

        // Return updated promPer with associated users and products
        return redirect()->route('admin.promotionPeriods.index')->with([
            'message' => 'promPer updated successfully!',
        ], 200);
    }



    public function destroyPromotionPeriod($id)
    {
        $promPer =  PromPer::findOrFail($id);
        $promPer->delete();
        return redirect()->back()->with('message' ,'promPer delEted successfully!',);
    }






}
