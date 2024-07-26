<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Public Routes


use App\Http\Controllers\FirebaseAparsController;

Route::get('/apartments', [FirebaseAparsController::class, 'index']);
Route::post('/apartments', [FirebaseAparsController::class, 'store']);
Route::get('/apartments/{id}', [FirebaseAparsController::class, 'show']);
Route::put('/apartments/{id}', [FirebaseAparsController::class, 'update']);
Route::delete('/apartments/{id}', [FirebaseAparsController::class, 'destroy']);

// Route::get('/apartments', [ApartmentController::class, 'index']); // No authentication needed
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register-admin', [AuthController::class, 'registerAdmin']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    // Route::post('/apartments', [ApartmentController::class, 'store']);
// Protected Routes


    // Route::put('/apartments/{apartment}', [ApartmentController::class, 'update']);
    // Route::delete('/apartments/{apartment}', [ApartmentController::class, 'destroy']);

    Route::post('/create-order', [PaymentController::class, 'createOrder']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
});

// Admin Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('/admin/apartments', [AdminController::class, 'getAllApartments']);
    Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
    
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::delete('/admin/apartments/{id}', [AdminController::class, 'deleteApartment']);
    Route::delete('/admin/bookings/{id}', [AdminController::class, 'deleteBooking']);

    // Example admin-only route for apartments (also available to admins)
    Route::get('/admin/apartments', [ApartmentController::class, 'adminIndex']);
});

Route::middleware(['auth:sanctum', 'admin'])->get('/admin/test', function () {
    return response()->json(['message' => 'Admin middleware is working']);
});
