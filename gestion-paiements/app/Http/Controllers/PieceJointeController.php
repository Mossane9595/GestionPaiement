<?php

namespace App\Http\Controllers;

use App\Models\PieceJointe;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PieceJointeController extends Controller
{
    /**
     * Créer et attacher une pièce jointe à un paiement
     */
    public function creer(Request $request, Paiement $paiement)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Sauvegarde du fichier
        $chemin = $request->file('fichier')->store('pieces_jointes', 'public');

        // Création de la pièce jointe en BDD
        $piece = $paiement->piecesJointes()->create([
            'nom' => $request->file('fichier')->getClientOriginalName(),
            'chemin' => $chemin,
        ]);

        return response()->json([
            'message' => 'Pièce jointe ajoutée avec succès',
            'piece' => $piece,
        ], 201);
    }

    /**
     * Télécharger une pièce jointe
     */
    public function telecharger(PieceJointe $piece)
    {
        if (!Storage::disk('public')->exists($piece->chemin)) {
            return response()->json(['message' => 'Fichier introuvable'], 404);
        }

        return Storage::disk('public')->download($piece->chemin, $piece->nom);
    }

    /**
     * Supprimer une pièce jointe
     */
    public function supprimer(PieceJointe $piece)
    {
        // Supprimer physiquement le fichier
        if (Storage::disk('public')->exists($piece->chemin)) {
            Storage::disk('public')->delete($piece->chemin);
        }

        // Supprimer l'enregistrement en BDD
        $piece->delete();

        return response()->json(['message' => 'Pièce jointe supprimée avec succès']);
    }
}
