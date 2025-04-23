<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DonationItemController;
use App\Http\Controllers\DonationTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DonationsCashController;
use App\Http\Controllers\EventLocationController;
use App\Http\Controllers\ExternalDonorController;
use App\Http\Controllers\CampaignFinanceController;
use App\Http\Controllers\DonationRequestController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\VolunteerVerificationController;
use App\Http\Controllers\DonationRequestDescriptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::resource('users', UserController::class);
Route::get('/users/editRol/{id}', [UserController::class, 'editRol'])->name('users.editRol'); 
Route::resource('donations', DonationController::class);
Route::resource('campaigns', CampaignController::class);
Route::resource('campaign-finances', CampaignFinanceController::class);
Route::resource('donations-cashes', DonationsCashController::class);
Route::resource('donation-items', DonationItemController::class);
Route::resource('donation-requests', DonationRequestController::class);
Route::resource('donation-request-descriptions', DonationRequestDescriptionController::class);
Route::resource('donation-types', DonationTypeController::class);
Route::resource('events', EventController::class);
Route::resource('event-locations', EventLocationController::class);
Route::resource('event-participants', EventParticipantController::class);
Route::resource('external-donors', ExternalDonorController::class);
Route::resource('financial-accounts', FinancialAccountController::class);
Route::resource('notifications', NotificationController::class);
Route::resource('profiles', ProfileController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('volunteer-verifications', VolunteerVerificationController::class);
Route::resource('roles', RoleController::class)->names('roles');