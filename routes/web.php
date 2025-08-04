<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PertaminaController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DocumentController;

// =======================
// AUTH & WELCOME ROUTES
// =======================
Route::get('/', fn () => view('welcome'))->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// ADMIN ROUTES
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Notifikasi
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/doc-read/{id}', [AdminController::class, 'markAsRead'])->name('read-doc');
    
    // ROUTE YANG DIPERBAIKI - menggunakan replyDoc method yang sudah diperbaiki
    Route::post('/reply-doc', [AdminController::class, 'replyDoc'])->name('reply-doc');
    
    // Upload dokumen dari form biasa (bukan reply)
    Route::post('/upload', [AdminController::class, 'upload'])->name('upload');
    Route::post('/kirim-pesan', [AdminController::class, 'kirimPesan'])->name('kirim-pesan');

    // Rekap Dokumen
    Route::get('/rekap', [AdminController::class, 'rekap'])->name('rekap');
    Route::put('/rekap/{id}/update-status', [AdminController::class, 'updateStatus'])->name('rekap.updateStatus');
    Route::delete('/rekap/{id}', [AdminController::class, 'destroy'])->name('rekap.destroy');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Vendor Management
    Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
    Route::get('/vendors/search', [AdminController::class, 'searchVendor'])->name('vendors.search');
    Route::post('/vendors/import', [AdminController::class, 'importVendors'])->name('vendors.import');
    Route::get('/vendors/{id}/edit', [AdminController::class, 'editVendor'])->name('vendors.edit');
    Route::put('/vendors/{id}', [AdminController::class, 'updateVendor'])->name('vendors.update');
    Route::delete('/vendors/delete-selected', [AdminController::class, 'bulkDelete'])->name('vendors.bulkDelete');

    // EPS List
    Route::get('/eps', [AdminController::class, 'eps'])->name('eps');

    // Dokumen Kesiapan (Readiness) - DIPERBAIKI
    Route::get('/doc-readiness', [DocumentController::class, 'index'])->name('doc.readiness');
    Route::post('/doc-readiness/upload', [DocumentController::class, 'upload'])->name('doc.readiness.upload');
    Route::post('/doc-readiness/send/{id}', [DocumentController::class, 'sendToPertamina'])->name('doc.readiness.send');
    Route::delete('/doc-readiness/{id}', [DocumentController::class, 'destroy'])->name('doc.readiness.delete');
    
    // TAMBAHAN: Alternative route jika menggunakan method docReadiness di AdminController
    // Route::get('/doc-readiness-alt', [AdminController::class, 'docReadiness'])->name('doc.readiness.alt');
});

// =======================
// PERTAMINA ROUTES
// =======================
Route::middleware(['auth', 'role:PERTAMINA'])->prefix('pertamina')->name('pertamina.')->group(function () {
    Route::get('/dashboard', [PertaminaController::class, 'dashboard'])->name('dashboard');
    Route::get('/upload', [PertaminaController::class, 'uploadForm'])->name('upload.form');
    Route::post('/upload', [PertaminaController::class, 'upload'])->name('upload.store');
});

// =======================
// HISTORY ROUTE (ALL ROLES)
// =======================
Route::middleware(['auth'])->get('/history', [HistoryController::class, 'index'])->name('history.index');