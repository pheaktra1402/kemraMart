<?php
 use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', fn()=>view('about'))->name('about');
    Route::get('/contact', fn()=>view('contact'))->name('contact');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
 
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
   

    Route::get('/userdashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Shop
    Route::get('/shop', [ShopController::class, 'shop'])->name('shop');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
   Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.store');
    Route::get('/orders/{order}/pay', [CheckoutController::class, 'pay'])->name('orders.pay');
    Route::get('/orders/{order}/check', [CheckoutController::class, 'checkPayment'])->name('orders.checkPayment');
    Route::get('/orders/{order}', [CheckoutController::class, 'show'])->name('orders.show');
});
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class)
    ->only(['index', 'edit', 'update', 'destroy']);

    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('stock/{product}/edit', [StockController::class, 'edit'])->name('stock.edit');
    Route::put('stock/{product}', [StockController::class, 'update'])->name('stock.update');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

});