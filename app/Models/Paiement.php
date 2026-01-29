<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'facture_id',
        'mois',
        'montant',
        'date_paiement',
        'mode_paiement',
        'reference',
    ];

    protected $casts = [
        'mois' => 'date',
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
