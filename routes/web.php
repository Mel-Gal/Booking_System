<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ProfileController; 

Route::redirect('/', '/login');

// Note: Assuming you are using Laravel Breeze or UI for the base auth routes
require __DIR__.'/auth.php'; 

Route::middleware(['auth'])->group(function () {
    
    // Everyone can see the dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CLIENTS: Only Admin and Receptionist can manage clients. Staff can only view.
    Route::prefix('clients')->name('clients.')->group(function () {

        Route::get('/', [ClientController::class, 'index'])->name('index');

        Route::middleware(['role:Admin,Receptionist'])->group(function () {
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        });

        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        
        Route::middleware(['role:Admin,Receptionist'])->group(function () {
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        });
    });

    // APPOINTMENTS: Everyone can view and update status. Admin/Receptionist can create and edit.
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
        
        Route::middleware(['role:Admin,Receptionist'])->group(function () {
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            
            // NEW: Added edit and update routes so your Reschedule button works!
            Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
            
            Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
        });
    });

    // SERVICE RECORDS: Admin and Staff can manage records.
    Route::prefix('service-records')->name('service-records.')->group(function () {
        
        // Staff and Admin can view and create
        Route::middleware(['role:Admin,Staff'])->group(function () {
            Route::get('/', [ServiceRecordController::class, 'index'])->name('index');
            Route::get('/create', [ServiceRecordController::class, 'create'])->name('create');
            
            // NEW: The missing show route to view specific records!
            Route::get('/{serviceRecord}', [ServiceRecordController::class, 'show'])->name('show');
            
            Route::get('/{serviceRecord}/edit', [ServiceRecordController::class, 'edit'])->name('edit');
            Route::put('/{serviceRecord}', [ServiceRecordController::class, 'update'])->name('update');
            Route::post('/', [ServiceRecordController::class, 'store'])->name('store');
        });

        // NEW: Only Admin can delete service records (based on your blade file logic)
        Route::middleware(['role:Admin'])->group(function () {
            Route::delete('/{serviceRecord}', [ServiceRecordController::class, 'destroy'])->name('destroy');
        });
    });

    // REPORTS: Admin Only!
    Route::middleware(['role:Admin'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });

    // USERS (Add Staff/Receptionist): Admin Only! <-- NEW: User Management Routes
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Breeze often uses PATCH
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});