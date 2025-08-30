<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    protected $table = 'modes_paiement';

    protected $fillable = ['nom', 'description', 'actif'];

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'mode_paiement_id');
    }
}
