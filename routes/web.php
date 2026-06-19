<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\DashboardController;
use Illuminate\Container\Attributes\Auth;

Route::get('/', function () {
    return view('welcome');
});

// AJAX Routes
Route::post('/ajax/greet', [AjaxController::class, 'greet'])->name('ajax.greet');
Route::get('/ajax/users', [AjaxController::class, 'getUsers'])->name('ajax.users');
Route::post('/ajax/store', [AjaxController::class, 'storeData'])->name('ajax.store');

//login page
Route::get('/login', [AjaxController::class, 'showLoginForm'])->name('login.form');
//register page
Route::get('/register', [AjaxController::class, 'showRegisterForm'])->name('register.form');

//logout
Route::get('/logout', [AjaxController::class, 'logout'])->name('logout');

// Authentication Routes
Route::post('/login', [AjaxController::class, 'login'])->name('login');
Route::post('/register', [AjaxController::class, 'register'])->name('register');
//update ticket status
Route::post('/update-ticket-status', [AjaxController::class, 'updateTicketStatus'])->name('update.ticket.status');

//submit ticket
Route::post('/submit-ticket', [AjaxController::class, 'submitTicket'])->name('submit.ticket');

// Protected Routes (Require Authentication)
Route::middleware('check.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
