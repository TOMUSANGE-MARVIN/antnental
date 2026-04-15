<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Doctor;
use App\Http\Controllers\Patient;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('doctors', Admin\DoctorController::class);
    Route::resource('patients', Admin\PatientController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::resource('appointments', Admin\AppointmentController::class)->only(['index', 'show', 'edit', 'update']);
    
    // Delivery Management Routes
    Route::resource('deliveries', Admin\DeliveryController::class);
    Route::get('deliveries/quick-add/{patient}', [Admin\DeliveryController::class, 'quickAdd'])->name('deliveries.quick-add');
    Route::post('deliveries/quick-store', [Admin\DeliveryController::class, 'quickStore'])->name('deliveries.quick-store');
});

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', [Doctor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Doctor\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [Doctor\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('appointments', Doctor\AppointmentController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('appointments/{appointment}/schedule-next', [Doctor\AppointmentController::class, 'scheduleNext'])->name('appointments.schedule-next');
    Route::resource('patients', Doctor\PatientController::class)->only(['index', 'show']);
    Route::post('patients/{patient}/schedule', [Doctor\PatientController::class, 'scheduleAppointment'])->name('patients.schedule');
});

// Patient Routes
Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', [Patient\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Patient\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [Patient\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('appointments', Patient\AppointmentController::class)->only(['index', 'create', 'store']);
});
