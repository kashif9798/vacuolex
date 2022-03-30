<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MicrobeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubCategoryController;
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

Route::get("/login", function(){
  return redirect()->route("login.form");
});

//storage-link
Route::get('/storage-link/create', function () {
  Artisan::call('storage:link');

  return "Storage Link created!";
});

// Clear cache
Route::get('/clear', function () {
  Artisan::call('optimize:clear');
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');
  
  return "Cache Cleared!";
});

// Application key generate
Route::get('/key/generate', function () {
  Artisan::call('key:generate');

  return "Key Generated!";
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

// Category Routes
Route::resource("categories", CategoryController::class)->except(["show"]);
Route::post("categories/search", [CategoryController::class, "index"])->name("categories.search");

// SubCategory Routes
Route::resource("subcategories", SubCategoryController::class)->except(["show"]);
Route::post("subcategories/search", [SubCategoryController::class, "index"])->name("subcategories.search");

// Admin Routes
Route::resource("admins", AdminController::class)->except(["show"]);
Route::post("admins/search", [AdminController::class, "index"])->name("admins.search");

// Subscribers Routes
Route::resource("subscribers", UserController::class)->only(["index", "destroy"]);
Route::post("subscribers/search", [UserController::class, "index"])->name("subscribers.search");

// Microbes Routes
Route::resource("microbes", MicrobeController::class)->except(["show"]);
Route::post("microbes/search", [MicrobeController::class, "index"])->name("microbes.search");
Route::post("microbes/repo/images", [MicrobeController::class, "repoImages"])->name("microbes.images");

});
