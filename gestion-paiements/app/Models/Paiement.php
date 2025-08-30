<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'utilisateur_id',
        'paiement_periodique_id',
        'mode_paiement_id',
        'description',
        'montant',
        'statut',
        'traite_a',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function paiement_periodique()
    {
        return $this->belongsTo(PaiementPeriodique::class, 'paiement_periodique_id');
    }

    public function mode_paiement()
    {
        return $this->belongsTo(ModePaiement::class, 'mode_paiement_id');
    }

    public function pieces_jointes()
    {
        return $this->hasMany(PieceJointe::class, 'paiement_id');
    }
}
