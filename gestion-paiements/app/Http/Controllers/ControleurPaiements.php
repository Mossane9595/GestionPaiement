<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\MockPaiement;
use App\Models\Paiement;
use App\Models\PieceJointe;


class ControleurPaiements extends Controller
{
   public function creer(Request $request)
{
    // 1. Vérifier que l'utilisateur a un compte
    $compte = $request->user()->compte; // Utilisation de l'utilisateur connecté
    if (!$compte) {
        return response()->json(['message' => 'Compte introuvable pour cet utilisateur'], 404);
    }

    // 2. Vérifier le solde
    if ($compte->solde < $request->montant) {
        return response()->json(['message' => 'Solde insuffisant'], 400);
    }

    // 3. Création du paiement dans la base
    $paiement = Paiement::create([
        'utilisateur_id' => $request->user()->id,
        'mode_paiement_id' => $request->mode_paiement_id,
        'paiement_periodique_id' => $request->paiement_periodique_id,
        'description' => $request->description,
        'montant' => $request->montant,
        'statut' => 'EN_ATTENTE',
    ]);

    // 4. Simulation du paiement via Mock
    $resultat = MockPaiement::traiterPaiement($paiement->montant, $paiement->description);

    // 5. Mise à jour du paiement
    $paiement->statut = $resultat['statut'];
    $paiement->traite_a = now();
    $paiement->save();

    // 6. Débiter le compte seulement si paiement réussi
    if ($paiement->statut === 'REUSSI') {
        $compte->solde -= $paiement->montant;
        $compte->save();
    }

    // 7. Retourner le paiement mis à jour
    return response()->json([
        'paiement' => $paiement,
        'solde_restante' => $compte->solde
    ]);
}

        public function lister(Request $requete)
    {
        $query = Paiement::query();

        if ($requete->has('jour')) {
            $query->whereDate('created_at', $requete->jour);
        }
        if ($requete->has('mois')) {
            $query->whereMonth('created_at', $requete->mois);
        }
        if ($requete->has('annee')) {
            $query->whereYear('created_at', $requete->annee);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }
    public function details($id)
    {
        $paiement = Paiement::findOrFail($id);
        return response()->json($paiement);
    }
}

