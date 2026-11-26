<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'pages.dashboard')->name('dashboard');
    Route::view('/profil', 'pages.profile')->name('profile');
    Route::view('/jadwal', 'pages.schedules')->name('schedules');
    Route::view('/tugas', 'pages.tasks')->name('tasks');
    Route::view('/keuangan', 'pages.finances')->name('finances');
    Route::view('/sampah', 'pages.trash')->name('trash');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
