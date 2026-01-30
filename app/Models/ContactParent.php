<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ContactParent extends Model
{
    use HasFactory;

    public const LIEN_PARENTAL_OPTIONS = ['pere', 'mere', 'tuteur', 'autre'];
    public const LIEN_PARENTAL_INPUTS = ['pere', 'père', 'papa', 'mere', 'mère', 'maman', 'tuteur', 'tutrice', 'autre'];

    protected $table = 'contacts_parents';

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

    public function setLienParentalAttribute(?string $value): void
    {
        $this->attributes['lien_parental'] = $this->normalizeLienParental($value);
    }

    private function normalizeLienParental(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = Str::of($value)->lower()->trim()->toString();

        if (in_array($normalized, self::LIEN_PARENTAL_OPTIONS, true)) {
            return $normalized;
        }

        $map = [
            'père' => 'pere',
            'papa' => 'pere',
            'mère' => 'mere',
            'maman' => 'mere',
            'tutrice' => 'tuteur',
        ];

        return $map[$normalized] ?? 'autre';
    }
}
