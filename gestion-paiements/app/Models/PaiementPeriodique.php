<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementPeriodique extends Model
{
    protected $table = 'paiements_periodiques';

    protected $fillable = [
        'utilisateur_id',
        'libelle',
        'fournisseur',
        'periodicite',
        'montant_defaut',
        'prochain_paiement',
        'actif'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'paiement_periodique_id');
    }
}
