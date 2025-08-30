<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControleurAuthentification;
use App\Http\Controllers\ControleurPaiements; 
use App\Http\Controllers\PieceJointeController;
use App\Http\Controllers\ModePaiementController;
use App\Http\Controllers\PaiementPeriodiqueController;
use App\Http\Controllers\CompteController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentification
Route::post('/inscription', [ControleurAuthentification::class, 'inscription']);
Route::post('/connexion', [ControleurAuthentification::class, 'connexion']);

// Paiements
Route::post('/paiements', [ControleurPaiements::class, 'creer']);
Route::get('/paiements', [ControleurPaiements::class, 'lister']);
Route::get('/paiements/{id}', [ControleurPaiements::class, 'details']);

// Ajouter une pièce jointe à un paiement
Route::post('/paiements/{paiement}/pieces', [PieceJointeController::class, 'creer']);

// Télécharger une pièce jointe
Route::get('/pieces/{piece}/telecharger', [PieceJointeController::class, 'telecharger']);

// Supprimer une pièce jointe
Route::delete('/pieces/{piece}', [PieceJointeController::class, 'supprimer']);

Route::get('/modes-paiement', [ModePaiementController::class, 'lister']);
Route::post('/modes-paiement', [ModePaiementController::class, 'creer']);
Route::put('/modes-paiement/{id}', [ModePaiementController::class, 'modifier']);
Route::delete('/modes-paiement/{id}', [ModePaiementController::class, 'supprimer']);

Route::get('/paiements-periodiques', [PaiementPeriodiqueController::class, 'lister']);
Route::post('/paiements-periodiques', [PaiementPeriodiqueController::class, 'creer']);
Route::put('/paiements-periodiques/{id}', [PaiementPeriodiqueController::class, 'modifier']);
Route::delete('/paiements-periodiques/{id}', [PaiementPeriodiqueController::class, 'supprimer']);

// Lister tous les comptes (admin)
Route::get('/comptes', [CompteController::class, 'lister']);

// Consulter le solde d'un utilisateur
Route::get('/comptes/{utilisateur_id}/solde', [CompteController::class, 'solde']);

// Créer un compte pour un utilisateur
Route::post('/comptes', [CompteController::class, 'creer']);

// Mettre à jour le solde d'un utilisateur
Route::put('/comptes/{utilisateur_id}/solde', [CompteController::class, 'mettreAJourSolde']);
Route::post('/comptes/{utilisateur_id}/recharger', [CompteController::class, 'recharger']);
