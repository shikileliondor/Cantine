<?php

namespace App\Livewire;

use App\Models\Classe;
use Livewire\Component;

class ClassesIndex extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $nom = '';
    public ?string $niveau = null;
    public ?string $annee_scolaire = null;

    protected function rules(): array
    {
        return [
            'nom' => 'required|string|max:120',
            'niveau' => 'nullable|string|max:120',
            'annee_scolaire' => 'nullable|string|max:50',
        ];
    }

    public function openForm(?int $id = null): void
    {
        $this->resetValidation();
        $this->editingId = $id;
        $this->showForm = true;

        if ($id) {
            $classe = Classe::findOrFail($id);
            $this->nom = $classe->nom;
            $this->niveau = $classe->niveau;
            $this->annee_scolaire = $classe->annee_scolaire;
            return;
        }

        $this->nom = '';
        $this->niveau = null;
        $this->annee_scolaire = null;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Classe::updateOrCreate(
            ['id' => $this->editingId],
            $validated
        );

        $this->closeForm();
        session()->flash('status', 'Classe enregistrée avec succès.');
    }

    public function delete(int $id): void
    {
        $classe = Classe::withCount('eleves')->findOrFail($id);

        if ($classe->eleves_count > 0) {
            session()->flash('status', 'Impossible de supprimer une classe avec des élèves.');
            return;
        }

        $classe->delete();
        session()->flash('status', 'Classe supprimée.');
    }

    public function render()
    {
        $classes = Classe::query()
            ->withCount('eleves')
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();

        return view('livewire.classes-index', [
            'classes' => $classes,
        ])->layout('layouts.app', ['header' => 'Élèves & Classes']);
    }
}
