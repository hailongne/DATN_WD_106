<?php
namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
class BannerController extends Controller
{
    // Hiển thị danh sách banner
    public function index()
    {
        $banners = Banner::all(); // Lấy tất cả các banner
        return view('admin.banners.index', compact('banners'));
    }

    // Thêm mới banner
    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'link' => 'nullable|url',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image_url' => $imagePath,
            'link' => $request->input('link'),
            'is_active' => true, // Mặc định banner mới luôn hoạt động
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được thêm thành công!');
    }

    // Chỉnh sửa banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->image_url = $imagePath;
        }

        $banner->link = $request->input('link');
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được cập nhật!');
    }

    // Xóa banner
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được xóa!');
    }
}


