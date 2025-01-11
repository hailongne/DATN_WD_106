<?php

namespace App\Http\Controllers\Admin;
use App\Components\CategoryRecusive;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{

    //


    public function listCategory(Request $request)
    {
        $categories = Category::where('name', 'like', '%' . $request->nhap . '%')
            ->orWhere('is_active', 'like', '%' . $request->nhap . '%')
            ->orWhere('slug', 'like', '%' . $request->nhap . '%')
            ->orWhere('description', 'like', '%' . $request->nhap . '%')
            ->orWhere('parent_id', 'like', '%' . $request->nhap . '%')
            ->latest()->paginate(5);
        return view('admin.pages.category.list', compact('categories'));
    }
    public function toggle($id)
    {
        $category = Category::findOrFail($id);

        // Thay đổi trạng thái is_active
        $category->is_active = !$category->is_active;
        $category->save();

        return redirect()->back()->with('success', 'Trạng thái danh mục đã được thay đổi!');
    }

    public function createCategory()
    {
        // Gọi phương thức getCategory với tham số parentId
        $categories = Category::callTreeCategory();
        // Trả về view với biến htmlOption
        return view('admin.pages.category.create', data: compact('categories'));
    }


    public function addCategory(CategoryRequest $request)
    {
        $anh=null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Tạo tên mới cho ảnh để tránh trùng lặp
            $newImage = time() . "." . $image->getClientOriginalExtension();

            // Lưu ảnh vào thư mục 'imagePro' trong thư mục 'public'
            $anh = $image->storeAs('images', $newImage, 'public');
        }else {
            // Nếu không có ảnh, sử dụng ảnh mặc định
            $anh = 'default.jpg';
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $anh, // Đảm bảo trường này không bao giờ null
            'parent_id' => $request->input('parent_id'),
            'slug' => str::slug($request->input('name')),
        ]);

        return redirect()->route('admin.categories.index')->with([
            'category' => $category,
            'success' => 'Thêm danh mục thành công!',
        ], 201);
    }
    public function detailCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.pages.category.detail', compact('category'));
    }
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::callTreeCategory(0,'',$id);  // Lấy danh mục con dưới dạng mảng

        return view('admin.pages.category.edit', compact('category', 'categories'));
    }
    public function updateCategory(CategoryRequest $request, $id)
    {

        $category = Category::findOrFail($id);
        $anh = $category->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($category->image);
            $image = $request->file('image');
            $newImage = time() . "." . $image->getClientOriginalExtension();
            $anh = $image->storeAs('images', $newImage, 'public');
        }
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->slug = $request->input('slug')
            ? Str::slug($request->input('slug'))
            : Str::slug($request->input('name'));
        $category->image = $anh;
        $category->parent_id = $request->input('parent_id');
        $category->save();
        return redirect()->route('admin.categories.index')
        ->with('success' ,'Cập nhật danh mục thành công!',);


    }
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);


        // Kiểm tra nếu có sản phẩm thuộc danh mục
        $productCount = Product::where('product_category_id', $id)->count();

        if ($productCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục này vì còn sản phẩm liên quan.');
        }
        $category->delete();
        return redirect()->back()->with('success' ,'Xóa danh mục thành công!',);
    }

}
