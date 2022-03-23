<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function(){
  return redirect()->route("login.form");
});

Route::prefix("admin")->group(function () {
// Authentication Routes
Route::get("/", [LoginController::class, "showLoginForm"])->name('login.form');
Route::post("/login", [LoginController::class, "login"])->name('login');
Route::post("/logout", [LoginController::class, "logout"])->name('logout');

// Forget & Reset Password Routes
Route::get('password/reset', [ForgotPasswordController::class,"showLinkRequestForm"])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class,"sendResetLinkEmail"])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, "showResetForm"])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, "reset"])->name('password.update');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


// Admin Profile Routes
Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
Route::put('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');

});
