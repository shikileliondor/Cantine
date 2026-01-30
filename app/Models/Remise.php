<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remise extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'facture_id',
        'libelle',
        'type_remise',
        'valeur',
        'actif',
        'commentaire',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
        'actif' => 'boolean',
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
