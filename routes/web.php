<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ===== Controllers (เติมให้ครบ กัน Class not found) =====
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;

// ===== Models (สำหรับ FE routes แบบ closure) =====
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Card;
use App\Models\OrderHeader;

// ====== หน้าเริ่มต้นของโปรเจกต์ (คงไว้ตามเดิม) ======
Route::get('/', function () {
    $categories = Category::select('category_id','name','image_url')->orderBy('category_id')->get();
    return view('homepage', compact('categories'));
})->name('homepage');

// ====== FE ROUTES (ไม่แตะ controller เดิม, ใช้ชื่อเส้นทางขึ้นต้น fe.*) ======

// Product List (public)
Route::get('/fe/products', function () {
    $categories = Category::orderBy('category_id')->get(['category_id','name']);
    $q = Product::with('category')->orderBy('product_id', 'asc');

    if (request('category_id')) $q->where('category_id', request('category_id'));
    if (request('search'))      $q->where('name','like','%'.request('search').'%');

    $products = $q->paginate(12)->withQueryString();
    return view('fe.products.index', compact('products','categories'));
})->name('fe.products.index');

// Product Detail (public)
Route::get('/fe/products/{id}', function ($id) {
    $product = Product::with('category')->findOrFail($id);

    $inWishlist = Auth::check()
        ? Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->product_id)->exists()
        : false;

    return view('fe.products.show', compact('product','inWishlist'));
})->name('fe.products.show');

// Wishlist (อ่านก่อน, ต้องล็อกอิน)
Route::middleware('auth')->get('/fe/wishlist', function () {
    $items = Wishlist::with('product.category')
        ->where('user_id', Auth::id())
        ->latest('wishlist_date')->get();

    return view('fe.wishlist.index', compact('items'));
})->name('fe.wishlist');

// Orders (อ่าน, ต้องล็อกอิน)
Route::middleware('auth')->get('/fe/orders', function () {
    $orders = OrderHeader::with('orderDetails.product','card')
        ->where('user_id', Auth::id())
        ->latest('order_date')->paginate(10);

    return view('fe.orders.index', compact('orders'));
})->name('fe.orders');

Route::resource('posts', PostController::class);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show'); 
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); 
    Route::get('/profile/image', [ProfileController::class, 'editImage'])->name('profile.image');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.image.update'); 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    Route::resource('cards', CardController::class)->only(['index','store','destroy']); 
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    Route::resource('products', ProductController::class); // เดิม (ต้องล็อกอิน)

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    /* ---------- Orders (แยกชัดเจน ไม่ใช้ resource เพื่อตัดชน) ---------- */

    // หน้า "ตะกร้า" (cart only)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // ประวัติคำสั่งซื้อ (delivering/placed/completed)
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // แสดงออเดอร์เดี่ยว
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // การจัดการรายการในตะกร้า
    Route::post('/order/add/{product}', [OrderController::class, 'addToOrder'])->name('orders.add');
    Route::patch('/order/detail/{detail}/decrement', [OrderController::class, 'decrementQty'])->name('orders.detail.decrement');
    Route::delete('/order/detail/{detail}', [OrderController::class, 'removeItem'])->name('orders.detail.destroy');

    // Checkout flow
    Route::get('/checkout',  [OrderController::class, 'checkoutPage'])->name('orders.checkout.page'); // ดูฟอร์ม
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('orders.checkout');  // ✅ ชื่อที่ถูกต้อง
});

require __DIR__.'/auth.php';
