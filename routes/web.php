<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationPublicController;
use Illuminate\Support\Facades\Route;




// =============================================
// Public Routes (Frontend Donasi)
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/campaigns', [HomeController::class, 'campaigns'])->name('public.campaigns');
Route::get('/campaign/{slug}', [DonationPublicController::class, 'show'])->name('public.campaign.show');
Route::post('/campaign/{slug}/donate', [DonationPublicController::class, 'donate'])->name('public.donation.store');
Route::get('/donation/success/{invoice}', [DonationPublicController::class, 'success'])->name('public.donation.success');

// =============================================
// Admin Login Route
// =============================================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


/**
 * route for admin
 */


//group route with prefix "admin"
Route::prefix('admin')->group(function () {


    //group route with middleware "auth"
    Route::group(['middleware' => 'auth'], function() {


        //route dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        //route resource categories
        Route::resource('/category', CategoryController::class,['as' => 'admin']);

        //route resource campaign
        Route::resource('/campaign', CampaignController::class, ['as' => 'admin']);

        //route donatur
        Route::get('/donatur', [DonaturController::class, 'index'])->name('admin.donatur.index');

        //route donation
        Route::get('/donation', [DonationController::class, 'index'])->name('admin.donation.index');
        Route::get('/donation/filter', [DonationController::class, 'filter'])->name('admin.donation.filter');
        Route::post('/donation/{id}/status', [DonationController::class, 'updateStatus'])->name('admin.donation.status');

        //route profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
            
        Route::resource('/slider', \App\Http\Controllers\Admin\SliderController::class, ['as' => 'admin']);

    });
});
