<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ColorRequest;
use App\Models\Color;
use App\Models\AttributeProduct;
class ColorController extends Controller
{
    //


    public function listColor(Request $request)
    {
        $colors = Color::where('name','like','%'. $request->nhap.'%')
        ->orWhere('color_code','like','%'. $request->nhap.'%')
        ->latest()->paginate(5);
        return view('admin.pages.color.list',compact('colors'));
    }
    public function createColor(Request $request)
    {

        return view('admin.pages.color.create');
    }

    public function addColor(ColorRequest $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color_code' => 'nullable|string|max:7',
        ]);

        $color = Color::create($validated);
        return redirect()->route('admin.colors.index')->with(['color'=>$color,'message' => 'Color add successfully!',],201);

}
public function detailColor($id)
{
    $color = Color::findOrFail($id);
    return view('admin.pages.color.detail',compact('color'));
}
public function editColor($id)
{
    $color = Color::findOrFail($id);
    return view('admin.pages.color.edit',compact('color'));
}
public function updateColor(ColorRequest $request,$id)
{
    $color = Color::findOrFail($id);
    $validated = $request->validate([
        'name' => 'required|string|max:50',
        'color_code' => 'nullable|string|max:7',
    ]);

    $color->update($validated);
    return redirect()->route('admin.colors.index')->with(['color'=>$color,'message' => 'Color add successfully!',],200);

}
public function destroyColor($id){
    $color=Color::findOrFail($id);
    $productCount = AttributeProduct::where('color_id', $id)->count();

        if ($productCount > 0) {
            return redirect()->back()->with('success', 'Không thể xóa màu sắc này vì còn sản phẩm liên quan.');
        }
    $color->delete();
    return redirect()->route('admin.colors.index')->with(['message' => 'color deleted successfully!',],200);
}
}
