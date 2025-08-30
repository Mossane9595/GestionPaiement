<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModePaiement;

class ModePaiementController extends Controller
{
    // Lister tous les modes de paiement
    public function lister()
    {
        return response()->json(ModePaiement::all());
    }

    // Créer un nouveau mode de paiement
    public function creer(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $mode = ModePaiement::create($request->all());

        return response()->json([
            'message' => 'Mode de paiement créé avec succès',
            'mode' => $mode
        ], 201);
    }

    // Modifier un mode de paiement
    public function modifier(Request $request, $id)
    {
        $mode = ModePaiement::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $mode->update($request->all());

        return response()->json([
            'message' => 'Mode de paiement mis à jour',
            'mode' => $mode
        ]);
    }

    // Supprimer un mode de paiement
    public function supprimer($id)
    {
        $mode = ModePaiement::findOrFail($id);
        $mode->delete();

        return response()->json(['message' => 'Mode de paiement supprimé']);
    }
}
