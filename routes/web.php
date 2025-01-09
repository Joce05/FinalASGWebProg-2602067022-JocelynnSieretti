<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TopupController;

Route::get('/explore/search', [ExploreController::class, 'search'])->name('explore.search');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/register/store', [RegisterController::class, 'register'])->name('register.post');

Route::get('/register/payment', [RegisterController::class, 'showPaymentForm'])->name('register.payment');
Route::post('/register/payment', [RegisterController::class, 'processPayment'])->name('register.payment.process');
Route::post('/register/payment/handle-overpay', [RegisterController::class, 'handleOverpayment'])->name('register.payment.handle-overpay');

Route::middleware('auth')->group(function () {
    // Route untuk menampilkan halaman profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route untuk update profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route for wishlist
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'viewWishlist'])->name('wishlist.view');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

Route::middleware('auth')->group(function () {
    // Route to show the list of friends
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // Route to start a chat with a friend
    Route::get('/chat/{friendid}', [ChatController::class, 'startChat'])->name('chat.start');

    // Route to send a message
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('messages.send');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
    Route::post('/topup/add-coins', [TopupController::class, 'addCoins'])->name('topup.add-coins');
    Route::post('/avatar/purchase', [TopupController::class, 'purchaseAvatar'])->name('avatar.purchase');
});
Route::get('/set-locale/{locale}', function($locale){
    if(in_array($locale,['en','id'])){
        session(['locale'=>$locale]);
    }
    return redirect()->back();
})->name('set-locale');

