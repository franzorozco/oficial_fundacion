<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonantesController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\VolunteerController;
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



/* PDFs Para reportes*/

Route::get('/campaigns/pdf', [CampaignController::class, 'generatePdf'])->name('campaigns.pdf.all');
Route::get('users/pdf', [UserController::class, 'generatePDF'])->name('users.pdf');
Route::get('/donantes/pdf', [DonantesController::class, 'generatePDF'])->name('donantes.pdf');
Route::get('/volunteers/pdf', [VolunteerController::class, 'generatePDF'])->name('volunteers.pdf');
Route::get('/donations/{id}/pdf', [DonationController::class, 'generatePdf'])->name('donations.pdf');
Route::get('/donations/pdf/all', [DonationController::class, 'generateAllDonationsPdf'])->name('donations.pdf.all');
Route::get('/campaign-finances/export-pdf', [CampaignFinanceController::class, 'exportPdf'])->name('campaign-finances.export-pdf');
Route::get('/donations-cashes/pdf', [DonationsCashController::class, 'exportPdf'])->name('donations-cashes.pdf');
Route::get('/donation-requests/pdf', [DonationRequestController::class, 'exportPdf'])->name('donation-requests.pdf');
Route::get('external-donors/pdf', [ExternalDonorController::class, 'generatePDF'])->name('external-donors.pdf');
Route::get('financial-accounts/pdf', [FinancialAccountController::class, 'generatePDF'])->name('financial-accounts.pdf');
Route::get('volunteer-verifications/pdf', [VolunteerVerificationController::class, 'generatePdf'])->name('volunteer-verifications.pdf');



/* Eliminaciones de tablas*/
Route::get('/users/editRol/{id}', [UserController::class, 'editRol'])->name('users.editRol'); 
Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

Route::get('donantes/trashed', [UserController::class, 'trashed'])->name('donantes.trashed');
Route::put('donantes/{id}/restore', [UserController::class, 'restore'])->name('donantes.restore');
Route::delete('donantes/{id}/force-delete', [UserController::class, 'forceDelete'])->name('donantes.forceDelete');

Route::get('volunteers/trashed', [UserController::class, 'trashed'])->name('volunteers.trashed');
Route::put('volunteers/{id}/restore', [UserController::class, 'restore'])->name('volunteers.restore');
Route::delete('volunteers/{id}/force-delete', [UserController::class, 'forceDelete'])->name('volunteers.forceDelete');


Route::get('roles/trashed', [RoleController::class, 'trashed'])->name('roles.trashed');
Route::put('/roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');

Route::get('donations/trashed', [DonationController::class, 'trashed'])->name('donations.trashed');
Route::put('donations/{id}/restore', [DonationController::class, 'restore'])->name('donations.restore');
Route::delete('donations/{id}/forceDelete', [DonationController::class, 'forceDelete'])->name('donations.forceDelete');

// Campañas eliminadas (soft deleted)
Route::get('campaigns/trashed', [CampaignController::class, 'trashed'])->name('campaigns.trashed');
Route::put('campaigns/{id}/restore', [CampaignController::class, 'restore'])->name('campaigns.restore');
Route::delete('campaigns/{id}/force-delete', [CampaignController::class, 'forceDelete'])->name('campaigns.forceDelete');

Route::get('campaign-finances/trashed', [CampaignFinanceController::class, 'trashed'])->name('campaign-finances.trashed');
Route::put('campaign-finances/{id}/restore', [CampaignFinanceController::class, 'restore'])->name('campaign-finances.restore');
Route::delete('campaign-finances/{id}/destroy-permanently', [CampaignFinanceController::class, 'destroyPermanently'])->name('campaign-finances.destroy-permanently');

Route::get('donations-cashes/trashed', [DonationsCashController::class, 'trashed'])->name('donations-cashes.trashed');
Route::post('donations-cashes/{id}/restore', [DonationsCashController::class, 'restore'])->name('donations-cashes.restore');
Route::delete('donations-cashes/{id}/force-delete', [DonationsCashController::class, 'forceDelete'])->name('donations-cashes.force-delete');

Route::get('donation-requests/trashed', [DonationRequestController::class, 'trashed'])->name('donation-requests.trashed');
Route::post('donation-requests/{id}/restore', [DonationRequestController::class, 'restore'])->name('donation-requests.restore');
Route::delete('donation-requests/{id}/force-delete', [DonationRequestController::class, 'forceDelete'])->name('donation-requests.force-delete');

Route::get('external-donors/trashed', [ExternalDonorController::class, 'trashed'])->name('external-donors.trashed');
Route::put('external-donors/{id}/restore', [ExternalDonorController::class, 'restore'])->name('external-donors.restore');
Route::delete('external-donors/{id}/force-delete', [ExternalDonorController::class, 'forceDelete'])->name('external-donors.forceDelete');


Route::get('financial-accounts/trashed', [FinancialAccountController::class, 'trashed'])->name('financial-accounts.trashed');
Route::patch('financial-accounts/{id}/restore', [FinancialAccountController::class, 'restore'])->name('financial-accounts.restore');
Route::delete('financial-accounts/{id}/force-delete', [FinancialAccountController::class, 'forceDelete'])->name('financial-accounts.forceDelete');

Route::get('volunteer-verifications/trashed', [VolunteerVerificationController::class, 'trashed'])->name('volunteer-verifications.trashed');
Route::put('volunteer-verifications/{id}/restore', [VolunteerVerificationController::class, 'restore'])->name('volunteer-verifications.restore');
Route::delete('volunteer-verifications/{id}/force-delete', [VolunteerVerificationController::class, 'forceDelete'])->name('volunteer-verifications.forceDelete');

Route::get('donation-request-descriptions/deleted', [DonationRequestDescriptionController::class, 'deleted'])->name('donation-request-descriptions.deleted');
Route::post('donation-request-descriptions/{id}/restore', [DonationRequestDescriptionController::class, 'restore'])->name('donation-request-descriptions.restore');
Route::delete('donation-request-descriptions/{id}/force-delete', [DonationRequestDescriptionController::class, 'forceDelete'])->name('donation-request-descriptions.force-delete');

Route::get('events/trashed', [EventController::class, 'trashed'])->name('events.trashed');
Route::post('events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
Route::delete('events/{id}/forceDelete', [EventController::class, 'forceDelete'])->name('events.forceDelete');

Route::get('event-locations/trashed', [EventLocationController::class, 'trashed'])->name('event-locations.trashed');
Route::post('event-locations/{id}/restore', [EventLocationController::class, 'restore'])->name('event-locations.restore');
Route::delete('event-locations/{id}/force-delete', [EventLocationController::class, 'forceDelete'])->name('event-locations.forceDelete');

Route::get('roles/trashed', [RoleController::class, 'trashed'])->name('roles.trashed');
Route::put('roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');








Route::get('/get-events-by-campaign/{id}', [EventParticipantController::class, 'getEventsByCampaign']);
Route::get('/get-locations-by-event/{id}', [EventParticipantController::class, 'getLocationsByEvent']);




Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::resource('users', UserController::class);
Route::resource('donantes', DonantesController::class);
Route::resource('volunteers', VolunteerController::class);
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
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
