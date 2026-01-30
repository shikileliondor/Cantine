<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\NoteEleve;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ElevesIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $classeFilter = '';
    public string $statutFilter = '';
    public int $perPage = 10;

    public ?int $detailEleveId = null;
    public ?int $noteEleveId = null;
    public ?int $classeChangeEleveId = null;

    public string $noteType = 'interne';
    public string $noteContenu = '';
    public ?int $classeChangeId = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'noteType' => 'required|string|max:50',
        'noteContenu' => 'required|string|min:3',
        'classeChangeId' => 'nullable|exists:classes,id',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingClasseFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatutFilter(): void
    {
        $this->resetPage();
    }

    public function getClassesProperty()
    {
        return Classe::query()
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();
    }

    public function getDetailEleveProperty(): ?Eleve
    {
        if (! $this->detailEleveId) {
            return null;
        }

        return Eleve::query()
            ->with(['classe', 'contactsParents', 'notesEleves', 'factures', 'paiements.facture'])
            ->find($this->detailEleveId);
    }

    public function openDetails(int $eleveId): void
    {
        $this->detailEleveId = $eleveId;
    }

    public function closeDetails(): void
    {
        $this->detailEleveId = null;
    }

    public function openNoteModal(int $eleveId): void
    {
        $this->resetValidation();
        $this->noteContenu = '';
        $this->noteType = 'interne';
        $this->noteEleveId = $eleveId;
    }

    public function closeNoteModal(): void
    {
        $this->noteEleveId = null;
    }

    public function saveNote(): void
    {
        $this->validateOnly('noteType');
        $this->validateOnly('noteContenu');

        if (! $this->noteEleveId) {
            return;
        }

        NoteEleve::create([
            'eleve_id' => $this->noteEleveId,
            'type_note' => $this->noteType,
            'contenu' => $this->noteContenu,
        ]);

        $this->closeNoteModal();
        session()->flash('status', 'Note ajoutée avec succès.');
    }

    public function openClasseModal(int $eleveId, ?int $classeId): void
    {
        $this->resetValidation();
        $this->classeChangeEleveId = $eleveId;
        $this->classeChangeId = $classeId;
    }

    public function closeClasseModal(): void
    {
        $this->classeChangeEleveId = null;
        $this->classeChangeId = null;
    }

    public function saveClasseChange(): void
    {
        $this->validateOnly('classeChangeId');

        if (! $this->classeChangeEleveId) {
            return;
        }

        $eleve = Eleve::find($this->classeChangeEleveId);
        if ($eleve) {
            $eleve->update([
                'classe_id' => $this->classeChangeId,
            ]);
        }

        $this->closeClasseModal();
        session()->flash('status', 'Classe mise à jour.');
    }

    public function cantineStatus(Eleve $eleve): array
    {
        $statut = $eleve->latestFacture?->statut;
        $normalized = $statut ? Str::of($statut)->lower()->replace('-', ' ')->toString() : '';

        $aJour = ['payee', 'paye', 'payé', 'reglee', 'reglée', 'a jour', 'a_jour', 'ok'];
        $partiel = ['partiel', 'partielle'];
        $retard = ['retard', 'impayee', 'impaye', 'impayé', 'en retard', 'en_retard'];

        if (! $normalized || in_array($normalized, $aJour, true)) {
            return ['label' => 'À jour', 'tone' => 'emerald', 'dot' => 'bg-emerald-400'];
        }

        if (in_array($normalized, $retard, true)) {
            return ['label' => 'Retard', 'tone' => 'rose', 'dot' => 'bg-rose-400'];
        }

        if (in_array($normalized, $partiel, true)) {
            return ['label' => 'Partiel', 'tone' => 'amber', 'dot' => 'bg-amber-300'];
        }

        return ['label' => 'Partiel', 'tone' => 'amber', 'dot' => 'bg-amber-300'];
    }

    public function render()
    {
        $eleves = Eleve::query()
            ->with(['classe', 'latestFacture'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('prenom', 'like', $search)
                        ->orWhere('nom', 'like', $search);
                });
            })
            ->when($this->classeFilter, function ($query) {
                $query->where('classe_id', $this->classeFilter);
            })
            ->when($this->statutFilter, function ($query) {
                $query->where('statut', $this->statutFilter);
            })
            ->orderBy('nom')
            ->paginate($this->perPage);

        return view('livewire.eleves-index', [
            'eleves' => $eleves,
            'classes' => $this->classes,
        ])->layout('layouts.app', ['header' => 'Élèves & Classes']);
    }
}
