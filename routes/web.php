<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\WorkDoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CarDocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CarImageController; // <-- Add this line
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// All routes in this group require a user to be logged in.
Route::middleware('auth')->group(function () {
    
    // Profile routes are available to all roles.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Admin Only Routes ---
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('reports/profitability', [ReportController::class, 'profitability'])->name('reports.profitability');
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });
    
    // --- Admin & Sales Routes ---
    Route::middleware('role:Admin,Sales')->group(function () {
        Route::resource('cars', CarController::class)->except(['show']);
        Route::get('/cars/{car}/cover-sheet', [CarController::class, 'generateCoverSheet'])->name('cars.cover_sheet');
        Route::get('/cars/{car}/purchase/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/cars/{car}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/cars/{car}/sale/create', [SalesController::class, 'create'])->name('sales.create');
        Route::post('/cars/{car}/sale', [SalesController::class, 'store'])->name('sales.store');

        // --- NEW: Car Image Management Routes ---
        Route::post('/cars/{car}/images', [CarImageController::class, 'store'])->name('car-images.store');
        Route::delete('/car-images/{image}', [CarImageController::class, 'destroy'])->name('car-images.destroy');
        Route::post('/car-images/{image}/set-primary', [CarImageController::class, 'setPrimary'])->name('car-images.set-primary');

    });
    
    // --- Admin, Sales & Mechanic Routes ---
    Route::middleware('role:Admin,Sales,Mechanic')->group(function() {
        Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
        Route::post('/cars/{car}/documents', [CarDocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}', [CarDocumentController::class, 'show'])->name('documents.show');
        Route::delete('/documents/{document}', [CarDocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/cars/{car}/work/create', [WorkDoneController::class, 'create'])->name('work.create');
        Route::post('/cars/{car}/work', [WorkDoneController::class, 'store'])->name('work.store');
        Route::get('/work/{workDone}', [WorkDoneController::class, 'show'])->name('work.show');
    });

});

require __DIR__.'/auth.php';
