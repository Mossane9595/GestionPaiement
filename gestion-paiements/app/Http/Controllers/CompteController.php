<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;

class CompteController extends Controller
{
    // Lister tous les comptes (admin)
    public function lister()
    {
        return response()->json(Compte::with('utilisateur')->get());
    }

    // Consulter le solde d'un compte utilisateur
    public function solde($utilisateur_id)
    {
        $compte = Compte::where('utilisateur_id', $utilisateur_id)->firstOrFail();

        return response()->json([
            'utilisateur_id' => $utilisateur_id,
            'solde' => $compte->solde
        ]);
    }

    // Créer un compte pour un utilisateur
    public function creer(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'solde' => 'numeric|min:0'
        ]);

        $compte = Compte::create($request->all());

        return response()->json([
            'message' => 'Compte créé avec succès',
            'compte' => $compte
        ], 201);
    }
    public function recharger(Request $request, $utilisateur_id)
{
    $request->validate([
        'montant' => 'required|numeric|min:0'
    ]);

    $compte = Compte::where('utilisateur_id', $utilisateur_id)->firstOrFail();
    $compte->solde += $request->montant; // on ajoute le montant
    $compte->save();

    return response()->json([
        'message' => 'Compte rechargé avec succès',
        'solde' => $compte->solde
    ]);
}


    // Mettre à jour le solde
    public function mettreAJourSolde(Request $request, $utilisateur_id)
    {
        $request->validate([
            'solde' => 'required|numeric|min:0'
        ]);

        $compte = Compte::where('utilisateur_id', $utilisateur_id)->firstOrFail();
        $compte->solde = $request->solde;
        $compte->save();

        return response()->json([
            'message' => 'Solde mis à jour avec succès',
            'compte' => $compte
        ]);
    }
}
