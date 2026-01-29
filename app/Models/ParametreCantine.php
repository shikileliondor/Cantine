<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreCantine extends Model
{
    use HasFactory;

    protected $table = 'parametres_cantine';

    protected $fillable = [
        'montant_mensuel',
        'jour_limite_paiement',
        'prorata_actif',
        'remises_autorisees',
    ];

    protected $casts = [
        'montant_mensuel' => 'decimal:2',
        'jour_limite_paiement' => 'integer',
        'prorata_actif' => 'boolean',
        'remises_autorisees' => 'boolean',
    ];
}
