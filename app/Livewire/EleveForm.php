<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\ContactParent;
use App\Models\Eleve;
use Livewire\Component;

class EleveForm extends Component
{
    public ?Eleve $eleve = null;

    public string $prenom = '';
    public string $nom = '';
    public ?string $date_naissance = null;
    public string $statut = 'actif';
    public ?int $classe_id = null;
    public ?string $notes_internes = null;
    public ?string $allergies_regime = null;

    public string $parent_nom = '';
    public ?string $parent_lien_parental = null;
    public ?string $parent_telephone_principal = null;
    public ?string $parent_telephone_secondaire = null;
    public ?string $parent_email = null;

    protected function rules(): array
    {
        return [
            'prenom' => 'required|string|max:120',
            'nom' => 'required|string|max:120',
            'date_naissance' => 'nullable|date',
            'statut' => 'required|in:actif,inactif',
            'classe_id' => 'nullable|exists:classes,id',
            'notes_internes' => 'nullable|string',
            'allergies_regime' => 'nullable|string|max:255',
            'parent_nom' => 'nullable|string|max:120',
            'parent_lien_parental' => 'nullable|string|max:120',
            'parent_telephone_principal' => 'nullable|string|max:30',
            'parent_telephone_secondaire' => 'nullable|string|max:30',
            'parent_email' => 'nullable|email|max:120',
        ];
    }

    public function mount(?Eleve $eleve = null): void
    {
        $this->eleve = $eleve;

        if (! $eleve) {
            return;
        }

        $this->prenom = $eleve->prenom;
        $this->nom = $eleve->nom;
        $this->date_naissance = optional($eleve->date_naissance)->format('Y-m-d');
        $this->statut = $eleve->statut;
        $this->classe_id = $eleve->classe_id;
        $this->notes_internes = $eleve->notes_internes;
        $this->allergies_regime = $eleve->allergies_regime;

        $contact = $eleve->contactsParents()->first();
        if ($contact) {
            $this->parent_nom = $contact->nom;
            $this->parent_lien_parental = $contact->lien_parental;
            $this->parent_telephone_principal = $contact->telephone_principal;
            $this->parent_telephone_secondaire = $contact->telephone_secondaire;
            $this->parent_email = $contact->email;
        }
    }

    public function getClassesProperty()
    {
        return Classe::query()
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();
    }

    public function save(): void
    {
        $validated = $this->validate();

        $eleveData = [
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'date_naissance' => $validated['date_naissance'],
            'statut' => $validated['statut'],
            'classe_id' => $validated['classe_id'],
            'notes_internes' => $validated['notes_internes'],
            'allergies_regime' => $validated['allergies_regime'],
        ];

        $eleve = $this->eleve;

        if ($eleve) {
            $eleve->update($eleveData);
        } else {
            $eleve = Eleve::create($eleveData);
        }

        $this->syncParent($eleve, $validated);

        session()->flash('status', 'Élève enregistré avec succès.');

        $this->redirectRoute('eleves.index');
    }

    private function syncParent(Eleve $eleve, array $validated): void
    {
        $hasParentData = $validated['parent_nom']
            || $validated['parent_telephone_principal']
            || $validated['parent_email'];

        if (! $hasParentData) {
            return;
        }

        ContactParent::updateOrCreate(
            [
                'eleve_id' => $eleve->id,
            ],
            [
                'nom' => $validated['parent_nom'],
                'lien_parental' => $validated['parent_lien_parental'],
                'telephone_principal' => $validated['parent_telephone_principal'],
                'telephone_secondaire' => $validated['parent_telephone_secondaire'],
                'email' => $validated['parent_email'],
            ]
        );
    }

    public function render()
    {
        $header = $this->eleve ? 'Modifier un élève' : 'Nouvel élève';

        return view('livewire.eleve-form', [
            'classes' => $this->classes,
        ])
            ->layout('layouts.app', ['header' => $header]);
    }
}
