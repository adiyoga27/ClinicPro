<?php

use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ThemeController;
use App\Livewire\Admin;
use App\Livewire\Auth;
use App\Livewire\Cashier;
use App\Livewire\Doctor;
use App\Livewire\Patient;
use App\Livewire\Public\LandingPage;
use App\Livewire\Superadmin;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', LandingPage::class)->name('landing');
Route::get('/login', Auth\Login::class)->name('login');

Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('landing');
})->middleware('auth')->name('logout');

// Subscription expired page
Route::get('/subscription/expired', function () {
    return view('subscription.expired');
})->middleware('auth')->name('subscription.expired');

// ============================================
// THEME TOGGLE ROUTE
// ============================================
Route::post('/theme/toggle', [ThemeController::class, 'toggle'])
    ->middleware('auth')
    ->name('theme.toggle');

// ============================================
// MIDTRANS WEBHOOK (no CSRF)
// ============================================
Route::post('/api/midtrans/notification', [MidtransController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ============================================
// SUPERADMIN ROUTES
// ============================================
Route::prefix('superadmin')
    ->middleware(['auth', 'role:superadmin'])
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', Superadmin\Dashboard::class)->name('dashboard');
    });

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')
    ->middleware(['auth', 'clinic.active', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', Admin\Dashboard::class)->name('dashboard');
        Route::get('/patients', Admin\PatientManager::class)->name('patients');
        Route::get('/queues', Admin\QueueManager::class)->name('queues');
        Route::get('/staff', Admin\StaffManager::class)->name('staff');
        Route::get('/medicines', Admin\MedicineManager::class)->name('medicines');
        Route::get('/services', Admin\ServiceManager::class)->name('services');
        Route::get('/rooms', Admin\RoomManager::class)->name('rooms');
        Route::get('/deposit', Admin\DepositManager::class)->name('deposit');
        Route::get('/subscription', Admin\SubscriptionManager::class)->name('subscription');
        Route::get('/satusehat', Admin\SatuSehatSettings::class)->name('satusehat');
        Route::get('/satusehat-logs', Admin\SatuSehatLogManager::class)->name('satusehat.logs');
    });

// ============================================
// DOCTOR ROUTES
// ============================================
Route::prefix('doctor')
    ->middleware(['auth', 'clinic.active', 'role:doctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/dashboard', Doctor\Dashboard::class)->name('dashboard');
        Route::get('/queues', Doctor\QueueList::class)->name('queues');
        Route::get('/examination/{queue}', Doctor\PatientExamination::class)->name('examination');
        Route::get('/patients', Doctor\PatientHistory::class)->name('patients');
    });

// ============================================
// CASHIER ROUTES
// ============================================
Route::prefix('cashier')
    ->middleware(['auth', 'clinic.active', 'role:cashier'])
    ->name('cashier.')
    ->group(function () {
        Route::get('/dashboard', Cashier\Dashboard::class)->name('dashboard');
        Route::get('/billing', Cashier\BillingManager::class)->name('billing');
        Route::get('/deposit', Admin\DepositManager::class)->name('deposit');
    });

// ============================================
// PATIENT ROUTES
// ============================================
Route::prefix('patient')
    ->middleware(['auth', 'role:patient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/dashboard', Patient\Dashboard::class)->name('dashboard');
    });
