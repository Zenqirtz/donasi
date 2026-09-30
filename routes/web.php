<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\DonationPublicController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// =============================================
// Public Routes (Frontend Donasi)
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/campaigns', [HomeController::class, 'campaigns'])->name('public.campaigns');
Route::get('/campaign/{slug}', [DonationPublicController::class, 'show'])->name('public.campaign.show');
Route::post('/campaign/{slug}/donate', [DonationPublicController::class, 'donate'])
    ->middleware('throttle:donation')
    ->name('public.donation.store');

// Halaman pembayaran / konfirmasi memakai signed URL supaya invoice
// tidak bisa ditebak atau dipakai menandai donasi orang lain.
Route::get('/donation/payment/{invoice}', [DonationPublicController::class, 'payment'])
    ->middleware('signed')
    ->name('public.donation.payment');
Route::post('/donation/confirm/{invoice}', [DonationPublicController::class, 'confirm'])
    ->middleware(['signed', 'throttle:donation'])
    ->name('public.donation.confirm');
Route::get('/donation/success/{invoice}', [DonationPublicController::class, 'success'])
    ->middleware('signed')
    ->name('public.donation.success');

// =============================================
// Admin Login Route
// =============================================
Route::get('/login', fn () => view('auth.login'))->name('login');

/**
 * route for admin
 */

// group route dengan prefix "admin" dan middleware auth + admin
Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        // route dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        // route resource categories
        Route::resource('/category', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.category');

        // route resource campaign
        Route::resource('/campaign', CampaignController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.campaign');

        // route donatur
        Route::get('/donatur', [DonaturController::class, 'index'])->name('admin.donatur.index');

        // route donation
        Route::get('/donation', [DonationController::class, 'index'])->name('admin.donation.index');
        Route::get('/donation/filter', [DonationController::class, 'filter'])->name('admin.donation.filter');
        Route::post('/donation/{id}/status', [DonationController::class, 'updateStatus'])->name('admin.donation.status');

        // route profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');

        // route resource slider
        Route::resource('/slider', SliderController::class)->only(['index', 'store', 'destroy'])->names('admin.slider');
    });
});
