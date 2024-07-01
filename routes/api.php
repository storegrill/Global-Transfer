<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MobileRechargeController;
use App\Http\Controllers\MultiLanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DigitalWalletController;
use App\Http\Controllers\ForeignTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/accounts', [AccountController::class, 'createAccount']);
    Route::get('/accounts', [AccountController::class, 'getAccounts'])->name('accounts.index');
    Route::post('/transfer', [AccountController::class, 'transfer']);
    Route::post('/logout', [AuthController::class, 'logout']); // Adding logout route for completeness

    // Investment routes
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/{id}', [InvestmentController::class, 'show'])->name('investments.show');
    Route::post('/investments', [InvestmentController::class, 'store']);
    Route::put('/investments/{id}', [InvestmentController::class, 'update']);
    Route::delete('/investments/{id}', [InvestmentController::class, 'destroy']);

    // Mobile Recharge routes
    Route::get('/mobile-recharges', [MobileRechargeController::class, 'index'])->name('mobile-recharges.index');
    Route::post('/mobile-recharges', [MobileRechargeController::class, 'store']);
    Route::get('/mobile-recharges/{id}', [MobileRechargeController::class, 'show']);
    Route::put('/mobile-recharges/{id}', [MobileRechargeController::class, 'update']);
    Route::delete('/mobile-recharges/{id}', [MobileRechargeController::class, 'destroy']);

    // Multi Language routes
    Route::post('/set-language', [MultiLanguageController::class, 'setLanguage']);
    Route::get('/get-language', [MultiLanguageController::class, 'getLanguage']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // Transaction routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);

    // Digital Wallet routes
    Route::get('/wallet', [DigitalWalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/deposit', [DigitalWalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [DigitalWalletController::class, 'withdraw'])->name('wallet.withdraw');

    // Transfer routes
    Route::post('/transfer', [ForeignTransactionController::class, 'transfer'])->name('foreign.transfer');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/accounts/{accountId}/transactions', [DashboardController::class, 'accountTransactions'])->name('dashboard.accountTransactions');
    Route::post('/transaction-summary', [DashboardController::class, 'transactionSummary'])->name('dashboard.transactionSummary');
});

// Exchange rate routes
Route::get('/exchange-rate/{baseCurrency}/{targetCurrency}', [ExchangeRateController::class, 'getExchangeRate']);
Route::post('/convert-currency', [ExchangeRateController::class, 'convertCurrency']);

// Geolocation routes
Route::post('/geolocation/coordinates', [GeolocationController::class, 'getCoordinates']);
Route::post('/geolocation/reverse-geocode', [GeolocationController::class, 'reverseGeocode']);

// Stripe routes
Route::post('/stripe/charge', [StripeController::class, 'createCharge'])->middleware('auth:sanctum');
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);

// Budget route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::get('/budgets/{id}', [BudgetController::class, 'show']);
    Route::put('/budgets/{id}', [BudgetController::class, 'update']);
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);
});


// Default Inertia route (catch-all for client-side routing)
Route::get('/{any}', function () {
    return Inertia::render('Error404'); // Assuming 'Error404' is your 404 page component
})->where('any', '.*');



// Controller route
Route::post('/register', [Controller::class, 'register']);
Route::post('/login', [Controller::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [Controller::class, 'logout']);
    Route::post('/create-account', [Controller::class, 'createAccount']);
    Route::get('/accounts', [Controller::class, 'getAccounts']);
    Route::post('/transfer', [Controller::class, 'transfer']);
});