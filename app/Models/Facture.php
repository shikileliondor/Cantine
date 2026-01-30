<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'mois',
        'montant_mensuel',
        'montant_remise',
        'montant_total',
        'date_limite',
        'statut',
    ];

    protected $casts = [
        'mois' => 'date',
        'montant_mensuel' => 'decimal:2',
        'montant_remise' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_limite' => 'date',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function remises(): HasMany
    {
        return $this->hasMany(Remise::class);
    }
}
