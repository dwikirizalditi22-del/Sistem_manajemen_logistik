<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    CategoryController,
    ProductController,
    PurchaseController,
    SaleController,
    ReportController,
    UserController,
    SupplierController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Ini adalah file route utama untuk aplikasi web Anda.
| Semua route yang memerlukan autentikasi dikelompokkan dengan middleware 'auth'.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Auth Routes (Guest Access)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Redirect root ke login
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard Redirect Otomatis (Link ke dashboard pakai route ini saja)
    Route::get('/dashboard-redirect', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard.redirect'); // Diganti nama route-nya agar tidak bentrok
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.index');
    // Route untuk admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');

    // Route untuk user biasa
  // Untuk user biasa
Route::get('/user/dashboard', [DashboardController::class, 'index'])
->name('user.dashboard')
->middleware('auth');

    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // Transaksi
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);

    // Laporan
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    });

    // Manajemen Pengguna
    Route::resource('users', UserController::class)->except(['show']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/laporan-produk-tanpa-harga', [ReportController::class, 'productsWithoutPrice']);
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
        // tambahkan route admin lainnya di sini
   
    


});
