<?php

use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController ;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PromotionPeriodController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\Dasboard;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentVnPayController;
use App\Http\Controllers\OrderController as OrderUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UserController as ProfileController;
use App\Http\Controllers\CouponController as UserCouponController;
Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['checkAdmin:admin,manager'] // Chỉ cho phép 'admin' và 'manager' truy cập
    ],
    function () {
        Route::get('/dashBoard', [StatsController::class, 'Stats'])->name('dashboard');
        Route::resource('/banners', BannerController::class)->names([
            'index' => 'banners.index',
            'create' => 'banners.create',
            'store' => 'banners.store',
            'show' => 'banners.show',
            'edit' => 'banners.edit',
            'update' => 'banners.update',
            'destroy' => 'banners.destroy',
        ]);
        // CRUD CATEGORY - Manager chỉ được xem danh mục
        Route::group(
            [
                'prefix' => 'categories',
                'as' => 'categories.',
                'middleware' => ['checkAdmin:admin,manager'] // Manager được phép xem danh mục
            ],
            function () {
                Route::get('/list-category', [CategoryController::class, 'listCategory'])->name('index');
                Route::get('/detail-category/{id}', [CategoryController::class, 'detailCategory'])->name('detail');


                // Admin mới có quyền CRUD
                Route::middleware('checkAdmin:admin')->group(function () {
                    Route::post('/categories/{id}/toggle', [CategoryController::class, 'toggle'])->name('toggle');
                    Route::get('/create-category', [CategoryController::class, 'createCategory'])->name('create');
                    Route::post('/add-category', [CategoryController::class, 'addCategory'])->name('store');
                    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroyCategory'])->name('delete');
                    Route::get('/edit-category/{id}', [CategoryController::class, 'editCategory'])->name('edit');
                    Route::put('/update-category/{id}', [CategoryController::class, 'updateCategory'])->name('update');
                });
            }
        );

        // CRUD BRAND
        Route::group(
            [
                'prefix' => 'brands',
                'as' => 'brands.',
                'middleware' => ['checkAdmin:admin,manager'] // Manager được phép xem nhưng không có quyền CRUD
            ],
            function () {
                Route::get('/list-brand', [BrandController::class, 'listBrand'])->name('index');
                Route::get('/detail-brand/{id}', [BrandController::class, 'detailBrand'])->name('detail');

                // Admin mới có quyền CRUD
                Route::middleware('checkAdmin:admin')->group(function () {
                    Route::post('/brands/{id}/toggle', [BrandController::class, 'toggle'])->name('toggle');
                    Route::get('/create-brand', [BrandController::class, 'createBrand'])->name('create');
                    Route::get('/edit-brand/{id}', [BrandController::class, 'editBrand'])->name('edit');
                    Route::post('/add-brand', [BrandController::class, 'addBrand'])->name('store');
                    Route::delete('/destroy-brand/{id}', [BrandController::class, 'destroyBrand'])->name('delete');
                    Route::put('/update-brand/{id}', [BrandController::class, 'updateBrand'])->name('update');
                });
            }
        );

        // Các route CRUD khác (Color, Size, Product, Coupon, Order) sẽ tương tự.
        // Đảm bảo rằng chỉ 'admin' có quyền thêm, sửa, xóa, còn 'manager' chỉ được xem thông tin.

        // Ví dụ CRUD COLOR
        Route::group(
            [
                'prefix' => 'colors',
                'as' => 'colors.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/list-color', [ColorController::class, 'listColor'])->name('index');
                Route::get('/detail-color/{id}', [ColorController::class, 'detailColor'])->name('detail');

                // Admin mới có quyền CRUD
                Route::middleware('checkAdmin:admin')->group(function () {
                    Route::get('/create-color', [ColorController::class, 'createColor'])->name('create');
                    Route::post('/add-color', [ColorController::class, 'addColor'])->name('store');
                    Route::get('/edit-color/{id}', [ColorController::class, 'editColor'])->name(name: 'edit');
                    Route::delete('/destroy-color/{id}', [ColorController::class, 'destroyColor'])->name('delete');
                    Route::put('/update-color/{id}', [ColorController::class, 'updateColor'])->name('update');
                });
            }
        );
        Route::group(
            [
                'prefix' => 'contacts',
                'as' => 'contacts.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/contacts', [AdminContactController::class, 'index'])->name('index');
                Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('show');
            }
        );
        // CRUD size
        Route::group(
            [
                'prefix' => 'sizes',
                'as' => 'sizes.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/list-size', [SizeController::class, 'listSize'])->name('index');

                Route::get('/create-size', [SizeController::class, 'createSize'])->name('create');
                Route::post('/add-size', [SizeController::class, 'addSize'])->name('store');
                Route::get('/detail-size/{id}', [SizeController::class, 'detailSize'])->name(name: 'detail');
                Route::get('/edit-size/{id}', [SizeController::class, 'editSize'])->name(name: 'edit');
                Route::delete('/destroy-size/{id}', [SizeController::class, 'destroySize'])->name('delete');
                Route::put('/update-size/{id}', [SizeController::class, 'updateSize'])->name('update');
            }
        );
        // CRUD PRODUCT
        Route::group(
            [
                'prefix' => 'products',
                'as' => 'products.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/list-product', [ProductController::class, 'listProduct'])->name(name: 'index');
                Route::post('/product/{id}/toggleHot', [ProductController::class, 'toggleHot'])->name('toggleHot');
                Route::post('/product/{id}/toggleBestSeller', [ProductController::class, 'toggleBestSeller'])->name('toggleBestSeller');
                Route::post('/product/{id}/toggle', [ProductController::class, 'toggle'])->name('toggle');
                Route::get('/get-data', [ProductController::class, 'getData'])->name('create');
                Route::post('/add-product', [ProductController::class, 'addProduct'])->name('store');
                Route::get('/get-data-atrpro/{id}', [ProductController::class, 'getDataAtrPro'])->name('getDataAtrPro');
                Route::post('/update-atrPro', [ProductController::class, 'updateAllAttributeProducts'])->name('updateAtrr');
                Route::get('/get-dataId/{id}', [ProductController::class, 'editProduct'])->name(name: 'edit');
                Route::get('/detail-product/{id}', [ProductController::class, 'detailProduct'])->name('detail');
                Route::delete('/destroy-product/{id}', [ProductController::class, 'destroyProduct'])->name('delete');
                Route::post('/products/{id}/restore', [ProductController::class, 'restoreProduct']);
                Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('update');;
            }
        );
        // CRUD COUPON
        Route::group(
            [
                'prefix' => 'coupons',
                'as' => 'coupons.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/list-coupon', [CouponController::class, 'listCoupon'])->name(name: 'index');
                Route::post('/coupon/{id}/toggle', [CouponController::class, 'toggle'])->name('toggle');
                Route::get('/create-coupon', [CouponController::class, 'createCoupon'])->name('create');
                Route::post('/add-coupon', [CouponController::class, 'addCoupon'])->name('store');
                Route::get('/detail-coupon/{id}', [CouponController::class, 'detailCoupon'])->name('detail');
                Route::get('/edit-coupon/{id}', [CouponController::class, 'editCoupon'])->name('edit');
                Route::delete('/destroy-coupon/{id}', [CouponController::class, 'destroyCoupon'])->name('delete');
                Route::put('/update-coupon/{id}', [CouponController::class, 'updateCoupon'])->name(name: 'update');
                Route::get('/admin/users/search', [CouponController::class, 'search'])->name('search');
                Route::get('/admin/users/search/{id}', [CouponController::class, 'searchId'])->name('searchId');

            }
        );
        // CRUD COUPON
        Route::group(
            [
                'prefix' => 'promotionPeriods',
                'as' => 'promotionPeriods.'
            ],
            function () {
                Route::get('/list-promotionPeriod', [PromotionPeriodController::class, 'listPromotionPeriod'])->name('index');
                Route::get('/create-promotionPeriod', [PromotionPeriodController::class, 'createPromotionPeriod'])->name('create');
                Route::post('/promotionPeriod/{id}/toggle', [PromotionPeriodController::class, 'toggle'])->name('toggle');
                Route::post('/add-promotionPeriod', [PromotionPeriodController::class, 'addPromotionPeriod'])->name('store');
                Route::get('/detail-promotionPeriod/{id}', [PromotionPeriodController::class, 'detailPromotionPeriod'])->name('detail');
                Route::get('/edit-promotionPeriod/{id}', [PromotionPeriodController::class, 'editPromotionPeriod'])->name('edit');
                Route::delete('/destroy-promotionPeriod/{id}', [PromotionPeriodController::class, 'destroyPromotionPeriod'])->name('delete');
                Route::put('/update-promotionPeriod/{id}', [PromotionPeriodController::class, 'updatePromotionPeriod'])->name('update');
            }
        );
        //Quản lí đánh giá
        Route::group(
            [
                'prefix' => 'reviews',
                'as' => 'reviews.'
            ],
            function () {
                Route::get('/list-review', [ReviewController::class, 'listReview'])->name('index');
                Route::get('/create-review', [ReviewController::class, 'createReview'])->name('create');
                Route::post('/review/{id}/toggle', [ReviewController::class, 'toggle'])->name('toggle');
                Route::get('/comments/{id}/reply', [ReviewController::class, 'reply'])->name('reply');
                Route::post('/comments/{id}/reply', [ReviewController::class, 'storeReply'])->name('storeReply');
                Route::get('/detail-review/{id}', [ReviewController::class, 'detailReview'])->name('detail');
                Route::get('/edit-review/{id}', [ReviewController::class, 'editReview'])->name('edit');
                Route::delete('/destroy-review/{id}', [ReviewController::class, 'destroyReview'])->name('delete');
                Route::put('/update-review/{id}', [ReviewController::class, 'updateReview'])->name('update');
            }
        );
    //Quản lí tồn kho
    Route::group(
        [
            'prefix' => 'inventories',
            'as' => 'inventories.'
        ],
        function () {
            Route::get('/list-product', [InventoryController::class, 'listProduct'])->name('index');
            Route::get('/detail-product/{id}', [InventoryController::class, 'detailProduct'])->name('detail');
            Route::get('/edit-quantity/{id}', [InventoryController::class, 'editQuantity'])->name('edit');
            Route::put('/update-stock/{id}', [InventoryController::class, 'updateStock'])->name('update');
        }
    );


        //Quản lý đơn hàng
        Route::group(
            [
                'prefix' => 'orders',
                'as' => 'orders.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('/order-history', [OrderUserController::class, 'orderHistory'])->name('history');
                Route::post('/order-confirm', [OrderUserController::class, 'confirmOrder'])->name('confirm');
                Route::post('/order-confirm_VNPay', [OrderUserController::class, 'confirmOrderVNPay'])->name('confirmVNPay');
                Route::post('/cancel-order/{orderId}', [OrderUserController::class, 'cancelOrder'])->name('cancelOrder');
                Route::post('/checkout/cod', function (Request $request) {
                    // Kiểm tra quyền người dùng
                    if (Auth::user()->role === 1 || Auth::user()->role === 3) {
                        // Nếu là admin hoặc manager, chuyển hướng về trang chủ với thông báo lỗi
                        return redirect()->route('home')->with('error', 'Bạn không có quyền mua hàng.');
                    }
                    // Nếu là user, thực hiện checkout
                    return app(PaymentController::class)->checkoutCOD($request);
                })->name('checkoutcod');
                Route::get('order/success', [PaymentController::class, 'orderSuccess'])->name('order-cod');
                Route::post('/checkout/cod', [PaymentController::class, 'checkoutCOD'])->name('checkoutcod');
                Route::get('/order/success', [PaymentController::class, 'orderSuccess'])->name('order-cod');
                Route::get('/order/{orderId}/detail', [OrderUserController::class, 'show'])->name('detail');
                Route::get('/user/orders/filter', [OrderController::class, 'filter'])->name('user.orders.filter');
            }
        );
        Route::group(
            [
                'prefix' => 'users',
                'as' => 'users.',
                'middleware' => ['checkAdmin:admin,manager']
            ],
            function () {
                Route::get('users', [UserController::class, 'listUser'])->name('listUser'); // Danh sách người dùng
                Route::get('users/{id}/edit-role', [UserController::class, 'edit'])->name('edit-role'); // Form chỉnh sửa role
                Route::post('users/{id}/update-role', [UserController::class, 'update'])->name('update-role'); // Cập nhật role
            }
        );
    }
);

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(
    [
        'middleware' => ['auth'], // Chỉ cho phép người dùng đã đăng nhập
        'as' => 'user.' // Tiền tố cho các route của người dùng
    ],
    function () {
        //danh mục sản phẩm
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');

        //sản phẩm
        Route::group(
            [
                'prefix' => 'product',
                'as' => 'product.',
            ],
            function () {
                Route::get('/search', [ProductsController::class, 'search'])->name('search');
                Route::get('product/{id}', [ProductsController::class, 'showProduct'])->name('detail');
                Route::get('/product-list/{categoryId?}', [ProductsController::class, 'productList'])->name('list');
                Route::get('/products/{categoryId?}', [ProductController::class, 'productList'])->name('user.proincate');
                Route::get('/color', [ColorController::class, 'index'])->name('color.index');
                Route::get('/size', [SizeController::class, 'index'])->name('size.index');
                Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
                Route::post('/comments', [ProductsController::class, 'addReview'])->name('addReview');
                Route::post('/like/{id}/toggle', [ProductsController::class, 'like'])->name('like');
                Route::post('/report/{id}/toggle', [ProductsController::class, 'report'])->name('report');
                Route::post('/love/{id}/toggle', [ProductsController::class, 'love'])->name('love');
                Route::get('/list-lovePro', [ProductsController::class, 'listLove'])->name('listLove');
            }
        );
        // Quản lý đơn hàng
        Route::group(
            [
                'prefix' => 'order',
                'as' => 'order.',
            ],
            function () {
                Route::get('/order-history', [OrderUserController::class, 'orderHistory'])->name('history');
                Route::post('/order-confirm', function (Request $request) {
                    // Kiểm tra quyền người dùng
                    if (Auth::user()->role === 1 || Auth::user()->role === 3) {
                        // Nếu là admin hoặc manager, chuyển hướng về trang chủ với thông báo lỗi
                        return redirect()->route('home')->with('error', 'Bạn không có quyền mua hàng.');
                    }
                    // Nếu là user, thực hiện checkout
                    return app(OrderUserController::class)->confirmOrder($request);
                })->name('confirm');
                Route::post('/order-confirm_VNPay', [OrderUserController::class, 'confirmOrderVNPay'])->name('confirmVNPay');
                Route::post('/cancel-order/{orderId}', [OrderUserController::class, 'cancelOrder'])->name('cancelOrder');
                Route::post('/checkout/cod', function (Request $request) {
                    // Kiểm tra quyền người dùng
                    if (Auth::user()->role === 1 || Auth::user()->role === 3) {
                        // Nếu là admin hoặc manager, chuyển hướng về trang chủ với thông báo lỗi
                        return redirect()->route('home')->with('error', 'Bạn không có quyền mua hàng.');
                    }
                    // Nếu là user, thực hiện checkout
                    return app(PaymentController::class)->checkoutCOD($request);
                })->name('checkoutcod');
                Route::get('order/success', [PaymentController::class, 'orderSuccess'])->name('order-cod');
                Route::get('/order/{orderId}/detail', [OrderUserController::class, 'show'])->name('detail');
                Route::get('/user/orders/filter', [OrderController::class, 'filter'])->name('user.orders.filter');
                Route::post('order/{orderId}/confirm-delivery', [OrderUserController::class, 'confirmDelivery'])->name('confirmDelivery');
            }
        );
        // Giỏ hàng
        Route::group(
            [
                'prefix' => 'cart',
                'as' => 'cart.',
            ],
            function () {
                Route::get('/get-cart-count', [CartController::class, 'getCartCount'])->name('getCartCount');

                Route::get('/cart-list', [CartController::class, 'viewCart'])->name('index');
                Route::post('/cart/add', [CartController::class, 'addToCart'])->name('add');
                Route::post('cart/buy-now', [CartController::class, 'buyNow'])->name('buy');
                Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('update');
                Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('remove');
                Route::get('/cart-popup', [CartController::class, 'viewCartPopup'])->name('popup');
            }
        );
        Route::group(
            [
                'prefix' => 'profiles',
                'as' => 'profiles.',

            ],
            function () {
                Route::get('/profiles', [ProfileController::class, 'showUserInfo'])->name('showUserInfo');
                Route::get('/profiles/{id}/edit', [ProfileController::class, 'edit'])->name('edit-profile');
                Route::post('/profiles/{id}/update', [ProfileController::class, 'updateUser'])->name('update-profile');
            }
        );
        Route::group(
            [
                'prefix' => 'coupons',
                'as' => 'coupons.',

            ],
            function () {
                Route::get('/coupons', [UserCouponController::class, 'listCoupon'])->name('list');
            }
        );
    }
);
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/apply-discount', [PaymentController::class, 'applyDiscount'])->name('user.order.applyDiscount');

Route::post('/vnp_payment', [PaymentVnPayController::class, 'vnp_payment'])
    ->name('checkout.vnpay');

Route::get('/vnp_return', [PaymentVnPayController::class, 'handleVNPayCallback'])
    ->name('vnpay.callback');

Route::get('/payment_vnpay', function () {
    return view('user.orders.orderVNPAY');
})->name('vnp.success');
Route::get('/payment_vnpay', function () {
    return view('user.orders.orderVNPAYFailed');
})->name('vnp.failed');

Route::get('about', function () {
    return view('user.about');
})->name('about');

Route::get('/orders', [OrderController::class, 'showAllOrders'])
    ->name('admin.orders');

Route::get('/orders-detail/{id}', [OrderController::class, 'showDetailOrder'])
    ->name('admin.orderDetail');

Route::post('/admin/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('admin.updateOrderStatus');

Route::get('/profile', function () {
    return view('user.profiles.index');
});

Route::get('/profile-edit', function () {
    return view('user.profiles.edit');
});
