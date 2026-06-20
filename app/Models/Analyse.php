<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Analyse extends Model
{
    protected $fillable = [
        'user_id',
        'situation',
        'type_plateforme',
        'categorie_probleme',
        'contexte_geo',
        'aspects',
        'categorie',
        'niveau_risque',
        'nature_probleme',
        'risques',
        'textes',
        'recommandations',
        'sanctions',
        'autorite',
    ];

    protected $casts = [
        'aspects'    => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeByNiveau($query, string $niveau)
    {
        return $query->where('niveau_risque', $niveau);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
