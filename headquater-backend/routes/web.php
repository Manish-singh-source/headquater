<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', [AuthController::class, 'loginCustomer'])->name('login');
Route::post('/login', [AuthController::class, 'loginAuthCheckCustomerData'])->name('login.auth.check');
Route::get('/register', [AuthController::class, 'registerCustomer'])->name('register');
Route::post('/register', [AuthController::class, 'registerCustomerData'])->name('register.store');

