<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'telephone',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Pour que Laravel sache quel champ contient le mot de passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'utilisateur_id');
    }

    public function paiements_periodiques()
    {
        return $this->hasMany(PaiementPeriodique::class, 'utilisateur_id');
    }

    public function compte()
    {
        return $this->hasOne(Compte::class, 'utilisateur_id');
    }
}
