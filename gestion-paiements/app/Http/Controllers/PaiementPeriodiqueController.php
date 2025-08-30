<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaiementPeriodique;

class PaiementPeriodiqueController extends Controller
{
    // Lister tous les paiements périodiques
    public function lister()
    {
        return response()->json(PaiementPeriodique::with('utilisateur')->get());
    }

    // Créer un paiement périodique
    public function creer(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'libelle' => 'required|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'periodicite' => 'required|in:quotidienne,hebdomadaire,mensuelle,annuelle',
            'montant_defaut' => 'nullable|numeric|min:0',
            'prochain_paiement' => 'nullable|date',
            'actif' => 'boolean'
        ]);

        $paiement = PaiementPeriodique::create($request->all());

        return response()->json([
            'message' => 'Paiement périodique créé avec succès',
            'paiement' => $paiement
        ], 201);
    }

    // Modifier un paiement périodique
    public function modifier(Request $request, $id)
    {
        $paiement = PaiementPeriodique::findOrFail($id);

        $request->validate([
            'libelle' => 'sometimes|required|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'periodicite' => 'sometimes|required|in:quotidienne,hebdomadaire,mensuelle,annuelle',
            'montant_defaut' => 'nullable|numeric|min:0',
            'prochain_paiement' => 'nullable|date',
            'actif' => 'boolean'
        ]);

        $paiement->update($request->all());

        return response()->json([
            'message' => 'Paiement périodique mis à jour',
            'paiement' => $paiement
        ]);
    }

    // Supprimer un paiement périodique
    public function supprimer($id)
    {
        $paiement = PaiementPeriodique::findOrFail($id);
        $paiement->delete();

        return response()->json(['message' => 'Paiement périodique supprimé']);
    }
}
