<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    protected $table = 'pieces_jointes';

    protected $fillable = [
        'paiement_id',
        'chemin',
        'type',
        'taille',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'paiement_id');
    }
    
}
