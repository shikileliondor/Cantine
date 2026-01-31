<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    use HasFactory;

    protected $table = 'annees_scolaires';

    protected $fillable = [
        'annee_debut',
        'annee_fin',
        'mois_personnalises',
        'coefficient_defaut',
        'trimestres',
        'est_active',
        'est_cloturee',
    ];

    protected $casts = [
        'annee_debut' => 'integer',
        'annee_fin' => 'integer',
        'mois_personnalises' => 'array',
        'coefficient_defaut' => 'decimal:2',
        'trimestres' => 'array',
        'est_active' => 'boolean',
        'est_cloturee' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('est_active', true)->where('est_cloturee', false);
    }

    public static function active(): ?self
    {
        return static::query()->active()->orderByDesc('annee_debut')->first();
    }

    public function getLibelleAttribute(): string
    {
        return $this->annee_debut . ' - ' . $this->annee_fin;
    }

    public function getTrimestreActifAttribute(): ?string
    {
        $trimestres = $this->trimestres ?? [];

        foreach ($trimestres as $numero => $periode) {
            $debut = data_get($periode, 'debut');
            $fin = data_get($periode, 'fin');

            if (! $debut || ! $fin) {
                continue;
            }

            $debutDate = \Carbon\Carbon::parse($debut)->startOfDay();
            $finDate = \Carbon\Carbon::parse($fin)->endOfDay();

            if (now()->between($debutDate, $finDate)) {
                return "Trimestre {$numero}";
            }
        }

        return null;
    }
}
