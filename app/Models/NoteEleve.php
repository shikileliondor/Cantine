<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteEleve extends Model
{
    use HasFactory;

    protected $table = 'notes_eleves';

    protected $fillable = [
        'eleve_id',
        'type_note',
        'contenu',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }
}
