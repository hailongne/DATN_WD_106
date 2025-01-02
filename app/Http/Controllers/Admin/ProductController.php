<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\AttributeRequest;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\Brand;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AttributeProduct;
use App\Models\Attribute;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
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
        return view('admin.pages.product.list')
            ->with(['products' => $products]);

    }
    public function toggle($id)
    {
        $product = Product::findOrFail($id);

        // Thay đổi trạng thái is_active
        $product->is_active = !$product->is_active;
        $product->save();

        return redirect()->back()->with('success', 'Trạng thái thương hiệu đã được thay đổi!');
    }

    public function getData()
    {
        $categories = Category::callTreeCategory();
        $brands = Brand::all();
        $sizes = Size::get();
        $colors = Color::get();
        return view(
            'admin.pages.product.create',
            compact('categories', 'brands', 'sizes', 'colors')
        );
    }
    public function addProduct(ProductRequest $request)
    {
        //vcalidate
        $request->validate([
            'color_id' => 'required|array', // color_id là mảng
            'color_id.*' => 'integer|exists:colors,color_id', // Kiểm tra từng phần tử trong mảng
            'size_id' => 'required|array', // size_id là mảng
            'size_id.*' => 'integer|exists:sizes,size_id', // Kiểm tra từng phần tử trong mảng
        ], [
            'color_id.required' => 'Vui lòng chọn màu.',
            'color_id.array' => 'ID màu sắc phải là một mảng.',
            'color_id.*.integer' => 'Mỗi ID màu sắc phải là một số nguyên.',
            'color_id.*.exists' => 'Màu sắc không tồn tại.',

            'size_id.required' => 'Vui lòng chọn ít nhất một kích thước.',
            'size_id.array' => 'ID kích thước phải là một mảng.',
            'size_id.*.integer' => 'Mỗi ID kích thước phải là một số nguyên.',
            'size_id.*.exists' => 'Kích thước không tồn tại.',
        ]);
        $image = null;
        if ($request->hasFile('main_image_url')) {
            $anh = $request->file('main_image_url');
            if ($anh->isValid()) {
                $newAnh = time() . "." . $anh->getClientOriginalExtension();
                // Save the image to the 'imagePro' directory in the public folder
                $image = $anh->move(public_path('storage/imagePro/'), $newAnh);
            }
        }

        // Create a new product using the request data
        $product = Product::create([
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'product_category_id' => $request->input('product_category_id'),
            'main_image_url' => $image ? 'imagePro/' . $image->getBasename() : null,  // Store the relative path/-strong/-heart:>:o:-((:-h 'view_count' => 0,
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'subtitle' => $request->input('subtitle'),
            'slug' => Str::slug($request->input('name')),
            'is_active' => $request->has('is_active') ? 1 : 0,  // Check if is_active is present
        ]);

        // Process color and size IDs (they could be comma-separated or an array)



        $colors = is_array($request->input('color_id')) ? $request->input('color_id') : explode(',', $request->input('color_id'));
        $sizes = is_array($request->input('size_id')) ? $request->input('size_id') : explode(',', $request->input('size_id'));


        // Prepare the data for the AttributeProduct table (product-color-size combinations)
        $productColorSizeData = [];
        foreach ($colors as $colorId) {
            foreach ($sizes as $sizeId) {
                $productColorSizeData[] = [
                    'product_id' => $product->product_id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                ];
            }
        }

        // Insert the attribute product data (color-size combinations)
        AttributeProduct::insert($productColorSizeData);

        return redirect()->route('admin.products.getDataAtrPro', ['id' => $product->product_id])->with('success', 'Thêm sản phẩm mới thành công!');
    }


    public function getDataAtrPro($id)
    {

        $productsAttPro = AttributeProduct::with([
            'product:product_id,name',
            'color:color_id,name',
            'size:size_id,name'
        ])
            ->where('product_id', $id)
            ->get();
        $groupedByColor = $productsAttPro->groupBy(function ($item) {
            return $item->color->name . "-" . $item->color->color_id;  // Group by both color name and color_id
        });

        return view('admin.pages.product.editAtrPro')
            ->with(['groupedByColor' => $groupedByColor, 'product_id' => $id]);
    }



    public function updateAllAttributeProducts(Request $request)
    {


        $attributeProducts = json_decode($request->input('attributeProducts', '[]'), true);
        $colorIds = $request->input('color_id', []);
        $product_id = $request->input('product_id', 0);
        $images = [];
        //validate


        // Xử lý từng color_id và ảnh tương ứng
        foreach ($colorIds as $colorId) {
            // Lấy ảnh của color_id này
            $colorImages = $request->file("images_{$colorId}");

            if ($colorImages) {
                // Lưu ảnh vào thư mục lưu trữ và thêm vào mảng images
                $storedImages = [];
                foreach ($colorImages as $image) {
                    $storedImages[] = $image->store('public/images/color_' . $colorId);
                }
                // Gán ảnh vào mảng theo color_id
                $images[$colorId] = $storedImages;
            }
        }

        // Lặp qua tất cả các sản phẩm thuộc tính trong mảng

        // Xử lý từng phần dữ liệu
        DB::beginTransaction();
        try {
            foreach ($attributeProducts as $product) {
                $attributeProduct = AttributeProduct::find($product['attribute_product_id']);
                if ($attributeProduct) {
                    $price = (float) str_replace(['.', ','], '', $product['prices']);
                    $inStock = (int) $product['in_stock'];
                    $attributeProduct->update([
                        'price' => $price,
                        'in_stock' => $inStock,
                    ]);
                }
            }

            foreach ($images as $key => $image) {
                // Lưu ảnh vào thư mục storage
                foreach ($image as $item) {
                    // log::info('1231231123123', [
                    //     'key' => json_encode($key),
                    //     'item' => json_encode($item),
                    //     'product_id' => json_encode($product_id)
                    // ]);
                    // Lưu đường dẫn ảnh vào cơ sở dữ liệu
                    DB::table('product_images')->insert([
                        'color_id' => $key,
                        'url' => (string) $item,
                        'product_id' => $product_id
                    ]);

                }


            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật Attribute Products:', ['message' => $e->getMessage()]);
        }
        return redirect()->route('admin.products.index')->with('success', 'Dữ liệu đã được cập nhật thành công.');


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

        return view('admin.pages.product.detail', compact('attPros'));
    }
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::callTreeCategory();

        $brands = Brand::get();
        $sizes = Size::get();
        $colors = Color::get();
        return view(
            'admin.pages.product.edit',
            compact('product', 'categories', 'brands', 'sizes', 'colors')
        );
    }
    public function updateProduct(ProductRequest $request, $id)
    {
        // Tìm sản phẩm cần cập nhật
        $product = Product::findOrFail($id);

        // Xử lý file ảnh mới
        $image = $product->main_image_url; // Lấy ảnh cũ (nếu có)
        if ($request->hasFile('main_image_url')) {
            // Nếu có ảnh mới, xóa ảnh cũ
            if ($product->main_image_url && File::exists(public_path($product->main_image_url))) {
                File::delete(public_path($product->main_image_url)); // Xóa ảnh cũ
            }

            $anh = $request->file('main_image_url');
            if ($anh->isValid()) {
                // Tạo tên ảnh mới
                $newAnh = time() . "." . $anh->getClientOriginalExtension();
                // Lưu ảnh vào thư mục 'imagePro'
                $image = $anh->move(public_path('storage/imagePro/'), $newAnh);
            }
        }

        // Cập nhật thông tin sản phẩm
        $product->update([
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'product_category_id' => $request->input('product_category_id'),
            'main_image_url' => $image ? 'imagePro/' . basename($image) : $product->main_image_url,
            'sku' => $request->input('sku'),
            'description' => $request->input('description', ''),
            'subtitle' => $request->input('subtitle'),
            'slug' => Str::slug($request->input('name')),
            'is_hot' => $request->has('is_hot') ? 1 : 0,
            'is_best_seller' => $request->has('is_best_seller') ? 1 : 0,

        ]);

        // Xử lý color và size IDs (có thể là mảng hoặc chuỗi phân cách bởi dấu phẩy)
        $colors = is_array($request->input('color_id')) ? $request->input('color_id') : explode(',', $request->input('color_id'));
        $sizes = is_array($request->input('size_id')) ? $request->input('size_id') : explode(',', $request->input('size_id'));

        // Xóa các kết nối cũ giữa sản phẩm và màu sắc/kích thước
        $product->colors()->detach();  // Xóa các màu sắc đã chọn cũ
        $product->sizes()->detach();   // Xóa các kích thước đã chọn cũ

        // Tạo dữ liệu mới cho bảng AttributeProduct (mối quan hệ sản phẩm - màu sắc - kích thước)
        $productColorSizeData = [];
        foreach ($colors as $colorId) {
            foreach ($sizes as $sizeId) {
                $productColorSizeData[] = [
                    'product_id' => $product->product_id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                ];
            }
        }

        // Cập nhật dữ liệu màu sắc và kích thước cho sản phẩm
        AttributeProduct::insert($productColorSizeData);
        return redirect()->route('admin.products.getDataAtrPro', ['id' => $product->product_id])
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!', );
    }
    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->back()->with('success', 'Khôi phục thành công!', );
    }
}
