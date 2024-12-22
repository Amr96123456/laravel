 <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\homecontorals;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admincontroller;
use App\Http\Controllers\Shopcontroller;
use App\Http\Controllers\userController;
use App\Http\Controllers\wishlistController;
use App\Http\Middleware\AuthAdmin;



Auth::routes();
    Route::get('/',[homecontorals::class,'index'])->name('home.index');
    Route::get ('/shop', [Shopcontroller::class,'index'])->name('shop.index');
    Route::get ('/shop/{product_slug}', [Shopcontroller::class, 'product_details'])->name('shop.prodect.details');

    Route::get ('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post ('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
    Route::put ('/cart/increase-qunatity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase.');
    Route::put ('/cart/decrease-qunatity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
    Route::delete ('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
    Route::delete ('/cart/clear', [CartController::class, 'empty_Cart'])->name('cart.empty');

    Route::post ('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
    Route::delete ('/cart/remove_coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');


    Route::post ('/wishlist/add', [wishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    Route::get ('/wishlist', [wishlistController::class, 'index'])->name('wishlist.index');
    Route::delete ('/wishlist/item/remove/{rowId}', [wishlistController::class, 'remove_item'])->name('wishlist.item.remove');
    Route::delete ('/wishlist/clear', [wishlistController::class, 'empty_wishlist'])->name('wishlist.item.clear');
    Route::post ('/wishlist/move_to_cart/{rowId}', [wishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

    Route::get ('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post ('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
    Route::get ('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');


    Route::middleware(['auth'])->group(function () {
        Route::get ('/account-dashboard', [ userController::class, 'index'])->name('user.index');
        Route::get ('/account-orders', [ userController::class, 'orders'])->name('user.orders');
        Route::get ('/account-order/{order_id}/details', [ userController::class, 'order_details'])->name('user.order.details');
        Route::put ('/account-order/cancel-order}/details', [ userController::class, 'order_cancel'])->name('user.order.cancel');


    });

    Route::middleware(['auth',AuthAdmin::class])->group(function () {
    Route::get ('/admin', [Admincontroller::class, 'index'])->name('admin.index');
    Route::get ('/admin/brands', [Admincontroller::class, 'brands'])->name('admin.brands');
    Route::get ('/admin/brand/add', [Admincontroller::class, 'add_brand'])->name('admin.brand.add');
    Route::post ('/admin/brand/store', [Admincontroller::class, 'brand_store'])->name('admin.brand.store');
    Route::get ('/admin/brand/edit/{id}', [Admincontroller::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put ('/admin/brand/update', [Admincontroller::class, 'brand_update'])->name('admin.brand.update');
    Route::delete ('/admin/brand/{id}/delete', [Admincontroller::class, 'brand_delete'])->name('admin.brand.delete');

    Route::get ('/admin/categories', [Admincontroller::class, 'categories'])->name('admin.categories');
    Route::get ('/admin/category/add', [Admincontroller::class, 'category_add'])->name('admin.category.add');
    Route::post ('/admin/category/store', [Admincontroller::class, 'category_store'])->name('admin.category.store');
    Route::get ('/admin/category/{id}/edit', [Admincontroller::class, 'category_edit'])->name('admin.category.edit');
    Route::put ('/admin/category/update', [Admincontroller::class, 'category_update'])->name('admin.category.update');
    Route::delete ('/admin/category/{id}/delete', [Admincontroller::class, 'categort_delete'])->name('admin.category.delete');

    Route::get ('/admin/products', [Admincontroller::class, 'products'])->name('admin.products');
    Route::get ('/admin/product/add', [Admincontroller::class, 'product_add'])->name('admin.product.add');
    Route::get ('/admin/product/store', [Admincontroller::class, 'product_store'])->name('admin.product.store');
    Route::get ('/admin/product/{id}/edit', [Admincontroller::class, 'product_edit'])->name('admin.product.edit');
    Route::put ('/admin/product/update', [Admincontroller::class, 'product_update'])->name('admin.product.update');
    Route::delete ('/admin/product/{id}/delete', [Admincontroller::class, 'product_delete'])->name('admin.product.delete');

    Route::get ('/admin/coupons', [Admincontroller::class, 'coupons'])->name('admin.coupons');
    Route::get ('/admin/coupon/add', [Admincontroller::class, 'coupon-add'])->name('admin.coupon.add');
    Route::post ('/admin/coupon/store', [Admincontroller::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get ('/admin/coupon/{id}/edit', [Admincontroller::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put ('/admin/coupon/update', [Admincontroller::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete ('/admin/coupon/{id}/delete', [Admincontroller::class, 'coupon_delete'])->name('admin.coupon.delete');

    Route::get ('/admin/orders', [Admincontroller::class, 'orders'])->name('admin.orders');
    Route::get ('/admin/{order_id}/details', [Admincontroller::class, 'order_details'])->name('admin.order.details');
    Route::put ('/admin/order/update-status', [Admincontroller::class, 'update_order_status'])->name('admin.order.status.update');

    Route::get ('/admin/slides', [Admincontroller::class, 'slides'])->name('admin.slides');
    Route::get ('/admin/slide/add', [Admincontroller::class, 'slide_add'])->name('admin.slide.add');
    Route::post ('/admin/slide/store', [Admincontroller::class, 'slide_store'])->name('admin.slide.store');
    Route::get ('/admin/slide/{id}/edit', [Admincontroller::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put ('/admin/slide/update', [Admincontroller::class, 'slide_update'])->name('admin.slide.update');
    Route::delete ('/admin/slide/{id}/delete', [Admincontroller::class, 'slide_delete'])->name('admin.slide.delete');







    });
