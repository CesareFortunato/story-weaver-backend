<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// =====================================================
// ROTTE ACCESSIBILI SOLO A UTENTI NON AUTENTICATI
// middleware: guest
// =====================================================

Route::middleware('guest')->group(function () {

    // Mostra il form di registrazione.
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // Salva il nuovo utente nel database.
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Mostra il form di login.
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Esegue il login dell'utente.
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Mostra il form per richiedere il reset password.
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Invia l'email con il link per il reset password.
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Mostra il form per inserire una nuova password.
    // Il token viene ricevuto via email.
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Salva la nuova password dell'utente.
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


// =====================================================
// ROTTE ACCESSIBILI SOLO A UTENTI AUTENTICATI
// middleware: auth
// =====================================================

Route::middleware('auth')->group(function () {

    // Mostra la pagina che chiede la verifica email.
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Verifica effettivamente l'email dell'utente.
    // middleware:
    // - signed => controlla che il link sia valido
    // - throttle => limita i tentativi
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Reinvia l'email di verifica.
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Mostra il form di conferma password.
    // Laravel lo usa per operazioni sensibili.
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    // Controlla la password inserita nel form di conferma.
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Aggiorna la password dell'utente autenticato.
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Esegue il logout dell'utente.
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});