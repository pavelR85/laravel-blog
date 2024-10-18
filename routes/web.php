<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthHomeController;
use \App\Http\Controllers\HomeController;
/*
 * old layout
Route::get('/home', function () {
    return view('layout');
})->name('home');
*/
Route::get('login', [AuthHomeController::class, 'index'])->name('login');
Route::post('post-login', [AuthHomeController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthHomeController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthHomeController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthHomeController::class, 'dashboard'])->middleware(['auth', 'verified']);
Route::post('logout', [AuthHomeController::class, 'logout'])->name('logout');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthHomeController::class, 'forgotPassword'])->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthHomeController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/', [HomeController::class, 'homePage'])->name('home-all');
Route::get('/{post_id}', [HomeController::class, 'homePage'])->name('post');

Route::get('/edit-post/{id}', [HomeController::class,'editPost'])->middleware(['auth'])->name('editPost');
Route::post('/save-post',[HomeController::class,'savePost'])->middleware(['auth'])->name('save.post');
Route::get('/remove-post/{id}', [HomeController::class,'deletePost'])->middleware(['auth'])->name('deletePost');
