<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    // API để thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập với thông báo
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $product = Product::findOrFail($request->product_id);

        // Kiểm tra nếu có lựa chọn màu sắc và kích thước
        $color = $request->color_id ? Color::find($request->color_id) : null;
        $size = $request->size_id ? Size::find($request->size_id) : null;

        // Lấy số lượng trong kho của sản phẩm theo màu sắc và kích thước
        $productAttribute = $product->attributeProducts()
            ->where('color_id', $color ? $color->color_id : null)
            ->where('size_id', $size ? $size->size_id : null)
            ->first();

        if (!$productAttribute) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm với màu sắc và kích thước đã chọn.'], 400);
        }

        $instock = $productAttribute->in_stock;

        // Tìm hoặc tạo giỏ hàng cho người dùng
        $cart = ShoppingCart::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng với màu sắc và kích thước đã chọn
        $cartItem = CartItem::where('shopping_cart_id', $cart->id)
            ->where('product_id', $product->product_id)
            ->where('color_id', $color ? $color->color_id : null)
            ->where('size_id', $size ? $size->size_id : null)
            ->first();

        // Tính tổng số lượng sản phẩm hiện tại trong giỏ hàng
        $currentQtyInCart = $cartItem ? $cartItem->qty : 0;
        $newQty = $request->qty;

        // Kiểm tra nếu số lượng muốn thêm cộng với số lượng hiện tại trong giỏ hàng vượt quá số lượng trong kho
        if ($currentQtyInCart + $newQty > $instock) {
            $maxQty = $instock - $currentQtyInCart;  // Tính số lượng tối đa có thể thêm vào giỏ hàng
            return response()->json([
                'error' => 'Chỉ còn lại ' . $maxQty . ' sản phẩm.'
            ], 400);  // Trả về mã lỗi 400
        }

        if ($cartItem) {
            // Nếu sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
            $cartItem->qty += $newQty;
            $cartItem->save();
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            CartItem::create([
                'shopping_cart_id' => $cart->id,
                'product_id' => $product->product_id,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'qty' => $newQty,
            ]);
        }

        // Trả về thông báo thành công
        return response()->json(['success' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
    }

    public function buyNow(Request $request)
    {
        if (!auth()->check()) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập với thông báo
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }
        $product = Product::findOrFail($request->product_id);

        // Kiểm tra nếu có lựa chọn màu sắc và kích thước
        $color = $request->color_id ? Color::find($request->color_id) : null;
        $size = $request->size_id ? Size::find($request->size_id) : null;


        // Tìm hoặc tạo giỏ hàng cho người dùng
        $cart = ShoppingCart::firstOrCreate([
            'user_id' => auth()->id() // Người dùng phải đăng nhập
        ]);

        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng với màu sắc và kích thước đã chọn
        $cartItem = CartItem::where('shopping_cart_id', $cart->id)
            ->where('product_id', $product->product_id)
            ->where('color_id', $color ? $color->color_id : null) // Nếu có màu sắc
            ->where('size_id', $size ? $size->size_id : null)   // Nếu có kích thước
            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã tồn tại, tăng số lượng
            $cartItem->qty += $request->qty;
            $cartItem->save();
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            CartItem::create([
                'shopping_cart_id' => $cart->id,
                'product_id' => $product->product_id,
                'color_id' => $request->color_id,   // Nếu có màu sắc
                'size_id' => $request->size_id,       // Nếu có kích thước
                'qty' => $request->qty,
                // 'price' => $product->price,
            ]);
        }
        // Trả về thông báo và điều hướng về trang giỏ hàng
        return redirect()->route('user.cart.index')->with('alert', 'Đã thêm sản phẩm vào giỏ hàng');
    }
    // API để xem giỏ hàng
    public function viewCart()
    {
        // Lấy ID người dùng đã đăng nhập
        $userId = Auth::id();

        // Nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        // Lấy giỏ hàng của người dùng
        $shoppingCart = ShoppingCart::where('user_id', $userId)
            ->with(['cartItems.product.attributeProducts.color', 'cartItems.product.attributeProducts.size']) // Eager load sản phẩm và thuộc tính màu sắc, kích thước
            ->first();

        // Nếu không tìm thấy giỏ hàng, trả về view với giỏ hàng rỗng
        if (!$shoppingCart) {
            return view('user.cart', [
                'cartItems' => [],
                'total' => 0,
                'discount' => 0,
                'shippingFee' => 40000, // Phí ship mặc định
                'finalTotal' => 40000, // Tổng cộng bao gồm phí ship
            ])->with('alert', 'Đây là trang giỏ hàng');
        }

        // Tính tổng tiền giỏ hàng
        $totalAmount = $shoppingCart->cartItems->sum(function ($item) {
            // Lấy giá của thuộc tính sản phẩm dựa trên size_id và color_id
            $attributeProduct = $item->product->attributeProducts
                ->where('size_id', $item->size_id)   // Lọc theo size_id
                ->where('color_id', $item->color_id) // Lọc theo color_id
                ->first();
            // Tính tổng tiền: số lượng * giá của thuộc tính sản phẩm
            return $item->qty * ($attributeProduct ? $attributeProduct->price : 0);
        });

        // Kiểm tra mã giảm giá nếu có
        $discount = 0;
        $couponCode = session('coupon_code'); // Lấy mã giảm giá từ session (nếu có)
        if ($couponCode) {
            // Áp dụng mã giảm giá nếu có
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                // Giảm giá theo tỷ lệ phần trăm hoặc giá trị cố định
                if ($coupon->type == 'percentage') {
                    $discount = ($coupon->discount / 100) * $totalAmount; // Giảm giá theo phần trăm
                } else {
                    $discount = $coupon->discount; // Giảm giá theo số tiền cố định
                }
            }
        }

        // Tổng tiền sau khi áp dụng giảm giá
        $totalAfterDiscount = $totalAmount - $discount;

        // Phí ship
        $shippingFee = 40000;

        // Tổng cộng sau khi cộng phí ship
        $finalTotal = $totalAfterDiscount + $shippingFee;

        // Kiểm tra số lượng tồn kho và truyền vào view
        foreach ($shoppingCart->cartItems as $item) {
            $product = $item->product;
            // Lấy số lượng tồn kho của sản phẩm
            $instock = $product->instock;

            // Thêm thông tin về số lượng tồn kho vào mỗi sản phẩm trong giỏ
            $item->instock = $instock;
            $item->canCheck = $item->qty <= $instock; // Kiểm tra xem số lượng trong giỏ có vượt quá tồn kho hay không
        }

        // Trả về dữ liệu giỏ hàng
        return view('user.cart', [
            'cartItems' => $shoppingCart->cartItems,
            'total' => $totalAmount,
            'discount' => $discount,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
            'couponCode' => $couponCode,
            'order' => $shoppingCart->latestOrder, // Giả sử bạn muốn truyền đơn hàng mới nhất của người dùng
        ]);
    }




    public function update(Request $request, $itemId)
    {
        try {
            // Lấy ID người dùng đã đăng nhập
            $userId = Auth::id();

            // Lấy giỏ hàng của người dùng
            $shoppingCart = ShoppingCart::where('user_id', $userId)
                ->with(['cartItems.product.attributeProducts.color', 'cartItems.product.attributeProducts.size'])
                ->first();

            // Nếu không có giỏ hàng, trả về lỗi
            if (!$shoppingCart) {
                return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại.']);
            }

            // Lấy sản phẩm trong giỏ hàng theo ID của item
            $item = CartItem::findOrFail($itemId);

            // Kiểm tra xem có sản phẩm nào khác với cùng màu sắc và kích thước trong giỏ hàng không (bỏ qua sản phẩm hiện tại)
            // $existingItem = $shoppingCart->cartItems->first(function ($cartItem) use ($request, $item) {
            //     return $cartItem->product_id == $item->product_id &&
            //            $cartItem->color_id == $request->input('color_id') &&
            //            $cartItem->size_id == $request->input('size_id') &&
            //            $cartItem->id != $item->id; // Bỏ qua chính sản phẩm hiện tại
            // });

            // Nếu đã tồn tại sản phẩm với màu sắc và kích thước này trong giỏ hàng (không tính sản phẩm hiện tại), thông báo lỗi
            // if ($existingItem) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Sản phẩm với màu sắc và kích thước này đã có trong giỏ hàng!',
            //         'confirm' => true // Yêu cầu xác nhận từ người dùng
            //     ]);
            // }

            // Nếu không có sản phẩm tương tự trong giỏ hàng, cập nhật lại sản phẩm hiện tại
            // $item->color_id = $request->input('color_id');
            // $item->size_id = $request->input('size_id');
            $item->qty = $request->input('quantity');
            $item->save();

        return response()->json(['success' => true, 'reload' => true]);
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra, trả về thông báo lỗi chung
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
    }




    public function removeItem($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('alert', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }


    public function viewCartPopup()
    {
        // Lấy ID người dùng đã đăng nhập
        $userId = Auth::id();

        // Nếu người dùng chưa đăng nhập, trả về lỗi
        if (!$userId) {
            return response()->json(['error' => 'Vui lòng đăng nhập để xem giỏ hàng.'], 403);
        }

        // Lấy giỏ hàng của người dùng
        $shoppingCart = ShoppingCart::where('user_id', $userId)
            ->with(['cartItems.product.attributeProducts.color', 'cartItems.product.attributeProducts.size']) // Eager load sản phẩm và thuộc tính màu sắc, kích thước
            ->first();

        // Nếu không tìm thấy giỏ hàng, trả về giỏ hàng rỗng
        if (!$shoppingCart) {
            return response()->json([
                'cartItems' => [],
                'total' => 0,
                'discount' => 0,
                'shippingFee' => 40000, // Phí ship mặc định
                'finalTotal' => 40000, // Tổng cộng bao gồm phí ship
            ]);
        }

        // Tính tổng tiền giỏ hàng
        $totalAmount = $shoppingCart->cartItems->sum(function ($item) {
            // Lấy giá từ bảng attribute_products qua quan hệ với product
            $attributeProduct = $item->product->attributeProducts->first();
            return $item->qty * ($attributeProduct ? $attributeProduct->price : 0);
        });

        $order = Order::where('user_id', auth()->id())->latest()->first();

        // Trả về dữ liệu giỏ hàng dưới dạng JSON
        return response()->json([
            'cartItems' => $shoppingCart->cartItems,
            'total' => $totalAmount,
            'order' => $order
        ]);
    }
    public function getCartCount()
    {
        $user = Auth::user();
        $cartCount = 0;

        if ($user) {
            $shoppingCart = ShoppingCart::where('user_id', $user->user_id)->first();
            if ($shoppingCart) {
                // Tính tổng số sản phẩm trong giỏ hàng
                $cartCount = $shoppingCart->cartItems->sum('qty');
            }
        }

        // Trả về số lượng sản phẩm dưới dạng JSON
        return response()->json(['cart_count' => $cartCount]);
    }
}
