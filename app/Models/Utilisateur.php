<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method bool isActive()
 */
class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'password' => 'hashed',
    ];

    // Auth methods
    public function getAuthIdentifierName()
    {
        return 'login'; 
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

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

    /**
     * Check if user is active and not expired
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if (!$this->actif) {
            return false;
        }
        
        if ($this->date_expiration && $this->date_expiration->isPast()) {
            return false;
        }
        
        return true;
    }
}