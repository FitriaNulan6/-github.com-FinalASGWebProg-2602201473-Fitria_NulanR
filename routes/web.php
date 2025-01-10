<?php
// routes/web.php
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Home & Profile
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Payment Routes
    Route::get('/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::post('/payment/handle-overpaid', [PaymentController::class, 'handleOverpaid'])->name('payments.handle-overpaid');

    // Messaging Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Thumb/Match Routes
    Route::post('/users/{user}/thumb', [HomeController::class, 'toggleThumb'])->name('users.thumb');

    // Avatar Routes
    Route::get('/avatars', [AvatarController::class, 'index'])->name('avatars.index');
    Route::post('/avatars/{avatar}/purchase', [AvatarController::class, 'purchase'])->name('avatars.purchase');
    Route::post('/avatars/send/{user}', [AvatarController::class, 'send'])->name('avatars.send');

    // Coin Routes
    Route::post('/coins/topup', [CoinController::class, 'topup'])->name('coins.topup');
    Route::post('/profile/toggle-visibility', [CoinController::class, 'toggleVisibility'])->name('profile.toggle-visibility');

    // Notification Routes
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
});

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
