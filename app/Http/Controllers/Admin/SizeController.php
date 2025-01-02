<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeProduct;
use App\Http\Requests\SizeRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Size;
class SizeController extends Controller
{
    //

    public function listSize(Request $request)
    {
        $sizes = Size::where('name', 'like', '%' . $request->nhap . '%')
            ->latest()->paginate(5);
            return view('admin.pages.size.list',compact('sizes'));
    }
    public function createSize(Request $request)
    {

        return view('admin.pages.size.create');
    }
    public function addSize(SizeRequest $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $size = Size::create($validated);
        return redirect()->route('admin.sizes.index')
        ->with(['size'=>$size,'success' => 'Thêm mới kích cỡ thành công!',],201);

    }
    public function detailSize($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.pages.size.detail',compact('size'));
    }
    public function editSize($id)
{
    $size = Size::findOrFail($id);
    return view('admin.pages.size.edit',compact('size'));
}
    public function updateSize(SizeRequest $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $size = Size::findOrFail($id);
        $size->name = $validated['name'];
        $size->save();
        return redirect()->route('admin.sizes.index')->with(['size'=>$size,'success' => 'Cập nhật kích cỡ thành công!',],200);

    }
    public function destroySize($id)
    {
        $size = Size::findOrFail($id);
        $productCount = AttributeProduct::where('size_id', $id)->count();

        if ($productCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa kích cỡ này vì còn sản phẩm liên quan.');
        }
        $size->delete();
        return redirect()->route('admin.sizes.index')->with(['success' => 'Kích cỡ xóa thành công!',],200);
    }
}
