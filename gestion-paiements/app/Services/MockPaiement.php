<?php

namespace App\Services;

class MockPaiement
{
    /**
     * Simule un paiement
     * @param float $montant
     * @param string $description
     * @return array ['statut' => string, 'reference' => string]
     */
    public static function traiterPaiement(float $montant, string $description): array
    {
        $random = rand(1, 100);

        if ($random <= 80) {
            $statut = 'REUSSI';
        } elseif ($random <= 95) {
            $statut = 'EN_ATTENTE';
        } else {
            $statut = 'ECHEC';
        }

        // Générer une référence fictive
        $reference = 'MOCK-' . strtoupper(uniqid());

        return [
            'statut' => $statut,
            'reference' => $reference,
            'montant' => $montant,
            'description' => $description,
        ];
    }
}
