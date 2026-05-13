<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostra il form del profilo utente.
     */
    public function edit(Request $request): View
    {
        // Passa alla view l'utente attualmente autenticato.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Aggiorna le informazioni del profilo utente.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Riempie il model User con i dati già validati dalla Form Request.
        $request->user()->fill($request->validated());

        // Se l'email è stata modificata,
        // resetta la verifica perché la nuova email va considerata non verificata.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Salva le modifiche dell'utente nel database.
        $request->user()->save();

        // Torna alla pagina profilo con un messaggio di stato.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina l'account dell'utente.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Valida la password prima di permettere l'eliminazione dell'account.
        // validateWithBag salva gli eventuali errori in una bag separata chiamata "userDeletion".
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Recupera l'utente autenticato.
        $user = $request->user();

        // Esegue il logout prima dell'eliminazione.
        Auth::logout();

        // Elimina l'utente dal database.
        $user->delete();

        // Invalida la sessione corrente per sicurezza.
        $request->session()->invalidate();

        // Rigenera il token CSRF per evitare riutilizzi della vecchia sessione.
        $request->session()->regenerateToken();

        // Reindirizza alla homepage.
        return Redirect::to('/');
    }
}