<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\WorkDoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CarDocumentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// All routes in this group require a user to be logged in.
Route::middleware('auth')->group(function () {
    
    // Profile routes are available to all roles.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Admin Only Routes ---
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/reports/profitability', [ReportController::class, 'profitability'])->name('reports.profitability');
    });

    // --- Admin & Sales Routes ---
    Route::middleware('role:Admin,Sales')->group(function () {
        Route::resource('cars', CarController::class)->except(['show']);
        Route::get('/cars/{car}/cover-sheet', [CarController::class, 'generateCoverSheet'])->name('cars.cover_sheet');
        Route::get('/cars/{car}/purchase/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/cars/{car}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/cars/{car}/sale/create', [SalesController::class, 'create'])->name('sales.create');
        Route::post('/cars/{car}/sale', [SalesController::class, 'store'])->name('sales.store');
    });
    
    // --- Admin, Sales & Mechanic Routes ---
    Route::middleware('role:Admin,Sales,Mechanic')->group(function() {
        // A mechanic or salesperson might need to view car details
        Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');

        // Document management routes
        Route::post('/cars/{car}/documents', [CarDocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}', [CarDocumentController::class, 'show'])->name('documents.show');
        Route::delete('/documents/{document}', [CarDocumentController::class, 'destroy'])->name('documents.destroy');
        
        // Work done routes
        Route::get('/cars/{car}/work/create', [WorkDoneController::class, 'create'])->name('work.create');
        Route::post('/cars/{car}/work', [WorkDoneController::class, 'store'])->name('work.store');
    });

});

require __DIR__.'/auth.php';
