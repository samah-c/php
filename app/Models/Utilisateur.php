<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;

    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom', 'prenom', 'login', 'password', 'email', 'telephone',
        'date_activation', 'date_expiration', 'actif', 'Unit_org', 'cree_par'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'actif' => 'boolean',
        'date_activation' => 'datetime',
        'date_expiration' => 'datetime',
    ];

    public function uniteOrganisationnelle()
    {
        return $this->belongsTo(UniteOrg::class, 'Unit_org', 'Num');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'utilisateur_id', 'id_utilisateur');
    }

    public function adminCreateur()
    {
        return $this->belongsTo(Admin::class, 'cree_par', 'id_admin');
    }
}
