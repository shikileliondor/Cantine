<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ClasseShow;
use App\Livewire\ClassesIndex;
use App\Livewire\EleveForm;
use App\Livewire\ElevesIndex;
use App\Livewire\FacturationIndex;
use App\Livewire\ParametresIndex;
use App\Livewire\TarifsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/eleves', ElevesIndex::class)->name('eleves.index');
    Route::get('/eleves/creer', EleveForm::class)->name('eleves.create');
    Route::get('/eleves/{eleve}/modifier', EleveForm::class)->name('eleves.edit');

    Route::get('/eleves/classes', ClassesIndex::class)->name('eleves.classes.index');
    Route::get('/eleves/classes/{classe}', ClasseShow::class)->name('eleves.classes.show');

    Route::get('/tarifs', TarifsIndex::class)->name('tarifs.index');
    Route::get('/facturation', FacturationIndex::class)->name('facturation.index');
    Route::get('/parametres', ParametresIndex::class)->name('parametres.index');
});

require __DIR__.'/auth.php';
