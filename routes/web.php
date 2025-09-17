<?php


use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('landing.home');
})->name('home');




Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class);
    });

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('payments', PaymentController::class);
    });

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', AdminUsersController::class);
    });

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('orders', OrderController::class);
    });

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('admin.products.index'))->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('products.variants', ProductVariantController::class)->shallow();
        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    });

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');


// inside routes/web.php
Route::middleware(['auth'])->group(function () {
    // resourceful routes for addresses (no show)
    Route::resource('addresses', AddressController::class)->except(['show']);
    // simple account/profile view (if you want)
    Route::get('account/profile', function () {
        return view('account.profile');
    })->name('account.profile');
});


Route::middleware('auth')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // thank you / order detail for customer
    Route::get('orders/{order}/thank-you', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');

    // list customer's orders
    Route::get('orders', [AccountOrderController::class, 'index'])->name('orders.index');
    // show single order
    Route::get('orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
    // reorder (POST)
    Route::post('orders/{order}/reorder', [AccountOrderController::class, 'reorder'])->name('orders.reorder');
});


Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
