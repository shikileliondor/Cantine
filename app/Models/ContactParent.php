<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'nom',
        'lien_parental',
        'telephone_principal',
        'telephone_secondaire',
        'email',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }
}
