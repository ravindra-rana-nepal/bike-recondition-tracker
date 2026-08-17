<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReconditioningLogController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes will be loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// Welcome page redirects to login
Route::get('/', function () {
    return view('welcome');
});

// Simple authentication routes (temporary - you can install Breeze later)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    
    // Simple authentication
    if ($credentials['email'] === 'admin@bike.com' && $credentials['password'] === 'password123') {
        session(['authenticated' => true, 'user_id' => 1, 'user_name' => 'Admin']);
        return redirect()->route('dashboard');
    }
    
    return back()->withErrors(['email' => 'Invalid credentials']);
})->name('login.submit');

Route::post('/logout', function () {
    session()->flush();
    return redirect('/');
})->name('logout');

// Protected routes
Route::middleware(['auth.simple'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Customer Routes
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{customer}/bikes', [CustomerController::class, 'bikes'])->name('customers.bikes');
    
    // Bike Routes
    Route::resource('bikes', BikeController::class);
    
    // Reconditioning Logs
    Route::prefix('bikes/{bike}')->group(function () {
        Route::get('logs', [ReconditioningLogController::class, 'index'])->name('bikes.logs.index');
        Route::get('logs/create', [ReconditioningLogController::class, 'create'])->name('bikes.logs.create');
        Route::post('logs', [ReconditioningLogController::class, 'store'])->name('bikes.logs.store');
        Route::delete('logs/{log}', [ReconditioningLogController::class, 'destroy'])->name('bikes.logs.destroy');
    });
    
    // Transactions
    Route::prefix('bikes/{bike}')->group(function () {
        Route::get('transactions', [TransactionController::class, 'index'])->name('bikes.transactions.index');
        Route::get('transactions/create', [TransactionController::class, 'create'])->name('bikes.transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('bikes.transactions.store');
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
        Route::get('/customers', [ReportController::class, 'customers'])->name('reports.customers');
    });
    
    // Quick Actions
    Route::post('/bikes/{bike}/mark-ready', [BikeController::class, 'markReady'])->name('bikes.mark-ready');
    Route::post('/bikes/{bike}/mark-sold', [BikeController::class, 'markSold'])->name('bikes.mark-sold');
});

// API routes (optional)
Route::prefix('api')->group(function () {
    Route::get('/bikes', function () {
        return \App\Models\Bike::with('seller')->paginate(10);
    });
    
    Route::get('/customers', function () {
        return \App\Models\Customer::paginate(10);
    });
});

// Test route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Bike Reconditioning System is running',
        'laravel_version' => app()->version()
    ]);
});