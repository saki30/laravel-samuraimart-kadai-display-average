<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminHomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 公開トップページ
Route::get('/', [WebController::class, 'index'])->name('top');

// 認証関連ルート（1回でOK）
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // 商品関連
    Route::resource('products', ProductController::class);

    // レビュー投稿
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // お気に入り機能
    Route::controller(FavoriteController::class)->group(function () {
        Route::get('/favorites', 'index')->name('favorites.index');
        Route::post('/favorites/{product_id}', 'store')->name('favorites.store');
        Route::delete('/favorites/{product_id}', 'destroy')->name('favorites.destroy');
    });

    // マイページ関連
    Route::controller(UserController::class)->group(function () {
        Route::get('users/mypage', 'mypage')->name('mypage');
        Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
        Route::put('users/mypage', 'update')->name('mypage.update');
        Route::get('users/mypage/password/edit', 'edit_password')->name('mypage.edit_password');
        Route::put('users/mypage/password', 'update_password')->name('mypage.update_password');      
        Route::get('users/mypage/favorite', 'favorite')->name('mypage.favorite');
        Route::delete('users/mypage/delete', 'destroy')->name('mypage.destroy');
        Route::get('users/mypage/cart_history', 'cart_history_index')->name('mypage.cart_history');
        Route::get('users/mypage/cart_history/{num}', 'cart_history_show')->name('mypage.cart_history_show');
    });

    // カート機能
    Route::controller(CartController::class)->group(function () {
        Route::get('users/carts', 'index')->name('carts.index');
        Route::post('users/carts', 'store')->name('carts.store');
        Route::delete('users/carts', 'destroy')->name('carts.destroy');
    });

    // チェックアウト
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('checkout', 'index')->name('checkout.index');
        Route::post('checkout', 'store')->name('checkout.store');
        Route::get('checkout/success', 'success')->name('checkout.success');
    });
});

// 公開用ルート
Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');

Route::prefix('admin')->name('admin.')->group(function () {
    // 管理者ログインページ
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);

    // 認証後の管理者ページ
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});
