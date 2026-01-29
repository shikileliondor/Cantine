<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'prenom',
        'nom',
        'date_naissance',
        'statut',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function contactsParents(): HasMany
    {
        return $this->hasMany(ContactParent::class);
    }

    public function notesEleves(): HasMany
    {
        return $this->hasMany(NoteEleve::class);
    }

    public function remises(): HasMany
    {
        return $this->hasMany(Remise::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
