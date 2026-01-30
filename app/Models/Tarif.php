<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe',
        'montant_mensuel',
        'debut_periode',
        'fin_periode',
        'actif',
    ];

    protected $casts = [
        'montant_mensuel' => 'decimal:2',
        'debut_periode' => 'date',
        'fin_periode' => 'date',
        'actif' => 'boolean',
    ];
}
