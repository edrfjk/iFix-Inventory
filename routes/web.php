<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AlertController;
 
// Redirect root to dashboard or login
Route::get('/', fn() => redirect('/dashboard'));
 
// Authentication Routes
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
 
// Protected Routes (require login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Products
    Route::resource('products', ProductController::class);
 
    // Stock Transactions
    Route::resource('transactions', StockTransactionController::class)->only([
        'index', 'create', 'store', 'show'
    ]);
 
    // Suppliers
    Route::resource('suppliers', SupplierController::class);
 
    // Categories
    Route::resource('categories', CategoryController::class);
 
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
 
    // Alerts
    Route::get('/alerts',           [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts/{id}/read',[AlertController::class, 'markRead'])->name('alerts.read');
    Route::post('/alerts/read-all', [AlertController::class, 'markAllRead'])->name('alerts.read-all');
});
