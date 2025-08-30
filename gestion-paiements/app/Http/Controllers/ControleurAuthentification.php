<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequeteInscription;
use App\Http\Requests\RequeteConnexion;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ControleurAuthentification extends Controller
{
    public function inscription(RequeteInscription $requete)
{
    // 1️⃣ Création de l'utilisateur
    $utilisateur = Utilisateur::create([
        'nom' => $requete->nom,
        'email' => $requete->email,
        'mot_de_passe' => Hash::make($requete->mot_de_passe),
    ]);

    // 2️⃣ Création automatique du compte avec solde initial 0
    $utilisateur->compte()->create([
        'solde' => 0
    ]);

    // 3️⃣ Retour JSON
    return response()->json([
        'message' => 'Inscription réussie, compte créé avec solde initial de 0',
        'utilisateur' => $utilisateur,
        'solde' => $utilisateur->compte->solde
    ], 201);
}


    public function connexion(RequeteConnexion $requete)
    {
        if (!Auth::attempt(['email' => $requete->email, 'password' => $requete->mot_de_passe])) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        $utilisateur = Auth::user();

        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $utilisateur
        ]);
    }
}
