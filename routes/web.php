<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\Admin\BeneficiaryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Strona główna
Route::get('/', function () {
    return view('welcome');
});

// Auth (logowanie/rejestracja)
Auth::routes();

// Dashboard użytkownika po zalogowaniu
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Panel użytkownika po zalogowaniu (stary HomeController)
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

// Przekierowanie do zajęć po slugu
Route::get('/meet/{slug}', [MeetingController::class, 'redirectToClass'])
    ->name('meet');

// ----------------------
// CRUD admina
// ----------------------
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Lista beneficjentów
    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])
        ->name('admin.beneficiaries.index');

    // Tworzenie nowego
    Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])
        ->name('admin.beneficiaries.create');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])
        ->name('admin.beneficiaries.store');

    // Edycja
    Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])
        ->name('admin.beneficiaries.edit');
    Route::put('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])
        ->name('admin.beneficiaries.update');

    // Usuwanie
    Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])
        ->name('admin.beneficiaries.destroy');

    Route::post('/beneficiaries/{beneficiary}/welcome-mail', [App\Http\Controllers\Admin\BeneficiaryController::class, 'sendWelcomeMail'])
        ->name('admin.beneficiaries.welcome-mail');

    Route::post('/beneficiaries/{beneficiary}/delete-user', [BeneficiaryController::class, 'deleteUser'])
        ->name('admin.beneficiaries.delete-user');
});
