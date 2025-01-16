<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AttributeRequest;
use App\Http\Requests\ProductRequest;
use App\Models\CartItem;
use App\Models\OrderItem;
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
        $categories = Category::select('category_id', 'name')->distinct()->get();
        $brands = Brand::select('brand_id', 'name')->distinct()->get();
    
        $products = Product::with('category:category_id,name', 'brand:brand_id,name')
        ->when($request->input('nhap'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('nhap') . '%')
                  ->orWhere('sku', 'like', '%' . $request->input('nhap') . '%'); // Sử dụng `sku` ở đây
            });
        })
            ->when($request->input('filter'), function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('category_id', $request->input('filter'));
                });
            })
            ->when($request->input('brand'), function ($query) use ($request) {
                $query->whereHas('brand', function ($q) use ($request) {
                    $q->where('brand_id', $request->input('brand'));
                });
            })
            ->latest()
            ->paginate(10);
    
        return view('admin.pages.product.list', compact('products', 'categories', 'brands'));
    }
    public function toggle($id)
    {
        $product = Product::findOrFail($id);

        // Thay đổi trạng thái is_active
        $product->is_active = !$product->is_active;
        $product->save();
        if (!$product->is_active) {
            // Xóa sản phẩm khỏi tất cả các giỏ hàng
            CartItem::where('product_id', $id)->delete();
        }
    
        return redirect()->back()->with('success', 'Trạng thái sản phẩm đã được thay đổi!');
    }
    public function toggleHot($id)
    {
        $product = Product::findOrFail($id);

        // Thay đổi trạng thái is_active
        $product->is_hot = !$product->is_hot;
        $product->save();

        return redirect()->back()->with('success', 'Trạng thái sản phẩm đã được thay đổi!');
    }
    public function toggleBestSeller($id)
    {
        $product = Product::findOrFail($id);

        // Thay đổi trạng thái is_active
        $product->is_best_seller = !$product->is_best_seller;
        $product->save();

        return redirect()->back()->with('success', 'Trạng thái sản phẩm đã được thay đổi!');
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
                // Tạo tên mới cho ảnh để tránh trùng lặp
                $newAnh = time() . "." . $anh->getClientOriginalExtension();
        
                // Lưu ảnh vào thư mục 'imagePro/images' trong storage/app/public
                $image = $anh->storeAs('imagePro', $newAnh, 'public');
            }
        } else {
            // Sử dụng ảnh mặc định nếu không có ảnh tải lên
            $image = 'imagePro/images/default.jpg'; // Đảm bảo file này tồn tại trong storage/app/public
        }
        

        // Create a new product using the request data
        $product = Product::create([
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'product_category_id' => $request->input('product_category_id'),
          'main_image_url' => $image ? 'imagePro/' . basename($image) : null, // Lấy tên file từ đường dẫn
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'subtitle' => $request->input('subtitle'),
            'slug' => Str::slug($request->input('name')),
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
            'size:size_id,name',
            'product.productImages'
        ])
            ->where('product_id', $id)
            ->get();
        $groupedByColor = $productsAttPro->groupBy(function ($item) {
            return $item->color->name . "-" . $item->color->color_id;  // Group by both color name and color_id
        });
        $colorId = $productsAttPro[0]->color_id; // Nếu lấy từ bảng AttributeProduct

        return view('admin.pages.product.editAtrPro')
            ->with(['groupedByColor' => $groupedByColor, 'product_id' => $id, 
            'colorId' => $colorId]);
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
                $oldImages = ProductImage::where('color_id', $colorId)->get(); // Truy vấn để lấy ảnh cũ theo color_id
    // Xóa ảnh cũ nếu có
    foreach ($oldImages as $oldImage) {
        // Xóa ảnh cũ khỏi storage
        Storage::disk('public')->delete('images/color_' . $colorId . '/' . $oldImage->url); // Giả định $oldImage->file_name là tên file ảnh cũ
    }
                // Lưu ảnh vào thư mục lưu trữ và thêm vào mảng images
                $storedImages = [];
                foreach ($colorImages as $image) {
                     $newAnh = time() . "." . $image->getClientOriginalExtension(); // Lấy phần mở rộng tệp gốc
                    $storedImages[] = $image->storeAs('/images/color_' . $colorId,$newAnh,'public');
                    
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
        return redirect()->route('admin.products.index')
            ->with('success', 'Dữ liệu đã được cập nhật thành công.');
    }
    public function detailProduct($id)
    {
        $attPros = AttributeProduct::with([
            'product:product_id,name,sku,is_best_seller,is_hot,is_active,main_image_url,description,slug,subtitle',
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

        // // Xóa các kết nối cũ giữa sản phẩm và màu sắc/kích thước
        // $product->colors()->detach();  // Xóa các màu sắc đã chọn cũ
        // $product->sizes()->detach();   // Xóa các kích thước đã chọn cũ

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
        foreach ($productColorSizeData as $data) {
            // Kiểm tra nếu bản ghi đã tồn tại trong bảng AttributeProduct
            $existingAttribute = AttributeProduct::where('product_id', $data['product_id'])
                                                 ->where('color_id', $data['color_id'])
                                                 ->where('size_id', $data['size_id'])
                                                 ->first();
            if ($existingAttribute) {
                // Cập nhật nếu bản ghi đã tồn tại
                $existingAttribute->update($data);
            } else {
                // Chèn mới nếu chưa có
                AttributeProduct::create($data);
            }
        }
        // AttributeProduct::insert($productColorSizeData);
        return redirect()->route('admin.products.getDataAtrPro', ['id' => $product->product_id])
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $productCount = OrderItem::where('product_id', $id)->count();
        if ($productCount > 0) {
            return redirect()->back()->with('success', 'Không thể xóa sản phẩm  này vì đang có trong đơn hàng.');
        }
        $product->delete();
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!',);
    }
    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->back()->with('success', 'Khôi phục thành công!',);
    }
}
