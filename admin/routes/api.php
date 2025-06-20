<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BillHandlerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/admin-login', [AdminAuthController::class, 'login']);
Route::post('/admin-logout', [AdminAuthController::class, 'logout']);
Route::get('/check-auth', [AdminAuthController::class, 'checkAuth'])->middleware(['auth:sanctum', 'web']);
Route::post('/create-staff', [AdminAuthController::class, 'createStaff']);

// Protected routes
Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin Profile Routes
    Route::get('/admin/profile', [AdminProfileController::class, 'show']);
    Route::post('/admin/profile/update', [AdminProfileController::class, 'update']);

    // Account Management Routes
    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'listAccounts']);
        Route::post('/staff', [AccountController::class, 'createStaffAccount']);
        Route::put('/staff/{id}', [AccountController::class, 'updateStaff']);
        Route::delete('/staff/{id}', [AccountController::class, 'deleteStaff']);
        Route::post('/customer', [AccountController::class, 'createCustomerAccount']);
        Route::post('/customer', [AccountController::class, 'createCustomer']);
        Route::put('/customer/{id}', [AccountController::class, 'updateCustomer']);
        Route::delete('/customer/{id}', [AccountController::class, 'deleteCustomer']);
        Route::post('/accounts/customer', [CustomerController::class, 'store']);
    });

    // Bill Handler Routes
    Route::prefix('bill-handler')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/bill-handler-dashboard', [BillHandlerController::class, 'BillHandlerDashboard']);
        Route::get('/customers', [BillHandlerController::class, 'getCustomers']);
    });

    // Rate Management Routes
    Route::get('/rates', [RateController::class, 'index']);
    Route::post('/rates', [RateController::class, 'store']);
    Route::put('/rates/{id}', [RateController::class, 'update']);
    Route::delete('/rates/{id}', [RateController::class, 'destroy']);

    // Announcement Routes
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);

    // Payment Routes
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/', [PaymentController::class, 'store']);
        Route::post('/{id}/approve', [PaymentController::class, 'approve']);
    });
});

// Temporary debug route
Route::get('/debug/check-admin', function() {
    $admins = DB::table('admin')->get();
    return response()->json([
        'admins' => $admins,
        'count' => $admins->count()
    ]);
});
