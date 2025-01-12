<?php

namespace App\Http\Controllers;
use App\Models\BannedWord;
use App\Models\Like;
use App\Models\LoveProduct;
use App\Models\Report;
use App\Models\ProductView;;
use App\Models\Reviews;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    // sản phẩm đang sale
    // public function saleProducts()
    // {
    //     $products = Product::where('is_sale', true)
    //         ->where('is_active', true)
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $products
    //     ]);
    // }

    // sản phẩm đang hot
    // public function hotProducts()
    // {
    //     $products = Product::where('is_hot', true)
    //         ->where('is_active', true)
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $products
    //     ]);
    // }

    // Sản phẩm bán chạy
    // public function bestSellingProducts()
    // {
    //     $products = Product::where('sold_count', '>', 0)
    //         ->where('is_active', true)
    //         ->orderBy('sold_count', 'desc')
    //         ->take(10) // Lấy top 10 sản phẩm bán chạy
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $products
    //     ]);
    // }


    // API để lấy danh sách sản phẩm
    public function productList($categoryId = null)
    {
        // Nếu có categoryId thì lọc theo danh mục, nếu không thì lấy tất cả sản phẩm
        if ($categoryId) {
            $listProduct = Product::with('attributeProducts')
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->get();
        } else {
            $listProduct = Product::with('attributeProducts')
                ->where('is_active', true)
                ->get();
        }
       
    
        // Lấy top 10 sản phẩm bán chạy (sold_count > 100) và đang hoạt động
        $bestSellers = Product::getBestSellers();
        $hotProducts = Product::getHotProducts();
        // Trả về view với dữ liệu
        return view('user.product', 
        compact('listProduct', 'hotProducts', 'bestSellers',))->with('alert', 'Bạn đang vào trang sản phẩm');
    }


    // API để lấy chi tiết một sản phẩm
    public function showProduct(Request $request, $productId)
    {
        // Tìm sản phẩm theo ID và kèm theo các thuộc tính của sản phẩm
        $product = Product::where('product_id', $productId)
            ->with(['attributeProducts.color', 'attributeProducts.size', 'attributeProducts']) // Eager load color and size attributes
            ->firstOrFail();
            
        // Nếu tìm thấy sản phẩm, xử lý số lượt xem
        if ($product) {
            // Thêm hoặc tìm bản ghi trong ProductView
            $productView = ProductView::firstOrCreate(
                [
                    'product_id' => $product->product_id,
                    'user_id' => Auth::id(),
                ],
                [
                    'view_count' => 0 // Giá trị mặc định khi thêm mới
                ]
            );
        
            // Tăng view_count
            $productView->increment('view_count');
        }
    
        // Hiển thị sản phẩm liên quan
        $relatedProducts = Product::where('product_category_id', $product->product_category_id)
            ->where('product_id', '!=', $product->product_id) // Loại trừ sản phẩm hiện tại
            ->where('is_active', 1) // Chỉ lấy sản phẩm đang hoạt động
            ->take(4) // Giới hạn 4 sản phẩm
            ->get();
    
        // Lấy comment của sản phẩm từ người dùng hiện tại
        $reviews = Reviews::where('product_id', $productId)
            ->where('user_id', auth()->id()) // Chỉ lấy đánh giá của người dùng hiện tại
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->whereHas('user', function ($userQuery) {
                        $userQuery->where('role', 1); // Chỉ lấy phản hồi của admin
                    });
                }
            ])
            ->get();
        
        // Lấy số sao từ query string (nếu không có thì trả về null)
        $rating = $request->query('rating');
    
        // Truy vấn lấy tất cả đánh giá của sản phẩm
        $query = Reviews::where('product_id', $productId)
            ->with('user', 'likes', 'reports') // Lấy thông tin người dùng đã đánh giá
            ->when($rating, function ($q) use ($rating) {
                // Lọc theo số sao nếu có
                $q->where('rating', $rating);
            });
    
        $reviewAll = $query->get();
    
        // Kiểm tra xem người dùng đã mua sản phẩm chưa
        $user = Auth::user();
        $hasPurchased = $user->orders()->whereHas('products', function ($query) use ($productId) {
            $query->where('order_items.product_id', $productId);
        })->exists();

        // Kiểm tra xem người dùng đã đánh giá sản phẩm chưa
        $hasReviewed = Reviews::where('product_id', $productId)
        ->where('user_id', Auth::id()) // Sử dụng Auth::id() để lấy đúng user_id
        ->exists();
        // Thêm thông báo vào session
        session()->flash('alert', 'Bạn đang vào trang chi tiết sản phẩm');
    
        // Trả về view với các biến cần thiết
        return view('user.detailProduct', compact('product', 'relatedProducts', 'reviews', 'reviewAll', 'rating', 'productId', 'hasPurchased', 'hasReviewed'));
    }
    
    public function addReview(Request $request)
    {
        $bannedWords = BannedWord::pluck('word')->toArray();
        $comment = $request->input('comment');
    
        // Kiểm tra các từ bị cấm trong bình luận
        foreach ($bannedWords as $bannedWord) {
            if (stripos($comment, $bannedWord) !== false) {
                $comment = str_ireplace($bannedWord, str_repeat('*', strlen($bannedWord)), $comment);
            }
        }
    
        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $existingReview = Reviews::where('product_id', $request->input('product_id'))
                                 ->where('user_id', auth()->id())
                                 ->first();
    
        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm này một lần.');
        }
    
        // Xử lý hình ảnh (nếu có)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $newImage = time() . "." . $image->getClientOriginalExtension();
            $anh = $image->storeAs('/storage/imagePro/image_review', $newImage, 'public');
        } else {
            $anh = '';
        }
    
        // Kiểm tra xem người dùng có chọn sao hay không
        $rating = $request->input('rating');
        if (empty($rating)) {
            return redirect()->back()->with('error', 'Vui lòng chọn sao :))');
        }
    
        // Thêm mới bình luận và đánh giá
        $review = Reviews::create([
            'product_id' => $request->input('product_id'), // Lưu product_id của review
            'user_id' => auth()->id(),
            'image' => $anh ?? null,
            'rating' => $rating ,
            'comment' => $comment ?: null,
        ]);
    
        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }
    
    public function like($reviewId)
    {
        $userId = auth()->id(); // ID của người dùng hiện tại

        // Kiểm tra nếu người dùng đã like đánh giá này rồi
        $existingLike = Like::where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->first();

        if ($existingLike) {
            // Nếu đã like, xóa like
            $existingLike->delete();
        } else {
            // Nếu chưa like, thêm like
            Like::create([
                'user_id' => $userId,
                'review_id' => $reviewId
            ]);
        }

        return back(); // Quay lại trang hiện tại
    }

    public function report(Request $request, $reviewId)
    {
        $userId = auth()->id(); // ID của người dùng hiện tại

        // Kiểm tra nếu người dùng đã like đánh giá này rồi
        $existingReport = Report::where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->first();

        if ($existingReport) {
            // Nếu đã like, xóa like
            $existingReport->delete();
        } else {
            // Nếu chưa like, thêm like
            Report::create([
                'user_id' => $userId,
                'review_id' => $reviewId
            ]);
        }

        return back(); // Quay lại trang hiện tại
    }
    public function love(Request $request, $productId)
    {
        $userId = auth()->id(); // ID của người dùng hiện tại

        // Kiểm tra nếu người dùng đã like đánh giá này rồi
        $existingReport = LoveProduct::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingReport) {
            // Nếu đã like, xóa like
            $existingReport->delete();
        } else {
            // Nếu chưa like, thêm like
            LoveProduct::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        }


        return redirect()->back()->with('success', 'Đã thêm vào danh sách.'); // Quay lại trang hiện tại
    }
    public function listLove()
    {
        $userId = auth()->id(); // ID của người dùng hiện tại

        $listProduct = Product::with('attributeProducts')
            ->whereHas('loveByUsers', function ($query) use ($userId) {
                $query->where('love_product.user_id', $userId); // Chỉ định bảng đúng cho cột 'user_id'
            })
            ->where('is_active', 1) // Lọc sản phẩm đang hoạt động
            ->get();

        return view('user.loveProduct', compact('listProduct'));
    }



}
